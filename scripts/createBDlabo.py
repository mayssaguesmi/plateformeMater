import pandas as pd
import mysql.connector
from slugify import slugify
import hashlib
import sys

# -------------------------
# CONFIG
# -------------------------
DB_CONFIG = {
    "host": "localhost",
    "user": "phpmyadmin",
    "password": "admin",
    "database": "utmsearch_w"
}

EXCEL_FILE = "Liste des Labos de recherche UTM.xlsx"

# -------------------------
# Connexion DB
# -------------------------
try:
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)
    print("✅ Connexion DB réussie")
except Exception as e:
    print(f"❌ Erreur connexion DB : {e}")
    sys.exit(1)

# -------------------------
# Fonction : trouver etablissement_id
# -------------------------
def get_etablissement_id(nom):
    try:
        if not nom:
            return None
        nom_clean = str(nom).strip().lower()
        cursor.execute("SELECT id, nom FROM utm_master_instituts")
        rows = cursor.fetchall()
        for r in rows:
            if (r["nom"].lower() == nom_clean or
                r["nom"].upper() == nom.upper() or
                r["nom"].capitalize() == nom.capitalize()):
                return r["id"]
    except Exception as e:
        print(f"⚠️ Erreur recherche établissement '{nom}': {e}")
    return None

# -------------------------
# Fonction : créer user WP
# -------------------------
def get_or_create_directeur(nom_responsable, email, etablissement_id):
    try:
        if not email:
            return None
        
        cursor.execute("SELECT ID FROM wp_users WHERE user_email=%s", (email,))
        row = cursor.fetchone()
        if row:
            return row["ID"]

        login = slugify(email.split("@")[0])
        pwd = hashlib.md5("changeme123".encode()).hexdigest()
        cursor.execute("""
            INSERT INTO wp_users (user_login, user_pass, user_nicename, user_email, display_name)
            VALUES (%s, %s, %s, %s, %s)
        """, (login, pwd, nom_responsable or login, email, nom_responsable or login))
        conn.commit()
        user_id = cursor.lastrowid

        cursor.execute("""
            INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES (%s, %s, %s)
        """, (user_id, "wp_capabilities", '{"um_directeur_laboratoire":true}'))

        if etablissement_id:
            cursor.execute("""
                INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES (%s, %s, %s)
            """, (user_id, "institut_id", str(etablissement_id)))

        conn.commit()
        print(f"👤 Utilisateur créé : {nom_responsable} ({email}) [ID={user_id}]")
        return user_id
    except Exception as e:
        print(f"⚠️ Erreur création user {email}: {e}")
        return None

# -------------------------
# Vérifier doublon labo
# -------------------------
def laboratoire_exists(code_lr, denomination):
    try:
        if code_lr:
            cursor.execute("SELECT id FROM utm_recherche_laboratoire WHERE code_lr=%s", (code_lr,))
            if cursor.fetchone():
                return True
        if denomination:
            cursor.execute("SELECT id FROM utm_recherche_laboratoire WHERE denomination=%s", (denomination,))
            if cursor.fetchone():
                return True
    except Exception as e:
        print(f"⚠️ Erreur check doublon labo : {e}")
    return False

# -------------------------
# Charger Excel (skip entêtes inutiles)
# -------------------------
try:
    df = pd.read_excel(EXCEL_FILE, skiprows=5)  # adapter skiprows si besoin
    print(f"📄 Fichier Excel chargé : {EXCEL_FILE}")
except Exception as e:
    print(f"❌ Erreur lecture Excel : {e}")
    sys.exit(1)

# Normaliser colonnes
df.columns = df.columns.str.strip().str.lower().str.replace(" ", "_")

# Mapping colonnes vers table SQL
df = df.rename(columns={
    "n°/etab": "numero_etab",
    "discipline": "discipline",
    "dénomination": "denomination",
    "التسمية": "denomination_ar",
    "code": "code_lr",
    "nom_du_responsable": "directeur_nom",
    "email": "directeur_email",
    "etablissement": "etablissement_label",
    "site_web": "site_web",
    "sr": "sr"
})

# -------------------------
# Parcours et insertion
# -------------------------
for _, row in df.iterrows():
    try:
        denomination = row.get("denomination")
        code_lr = row.get("code_lr")

        # Ignorer si infos essentielles manquantes
        if not denomination or pd.isna(denomination):
            print("⏩ Ligne ignorée : pas de denomination")
            continue
        if not code_lr or pd.isna(code_lr):
            print(f"⏩ Ligne ignorée ({denomination}) : pas de code_lr")
            continue

        # Vérifier doublons
        if laboratoire_exists(code_lr, denomination):
            print(f"⚠️ Labo déjà existant : {denomination} ({code_lr}) → ignoré")
            continue

        etablissement_id = get_etablissement_id(row.get("etablissement_label"))
        directeur_user_id = get_or_create_directeur(
            row.get("directeur_nom"), row.get("directeur_email"), etablissement_id
        )

        cursor.execute("""
            INSERT INTO utm_recherche_laboratoire 
            (numero_etab, sr, domaine, denomination, denomination_ar, code_lr,
             directeur_nom, directeur_email, directeur_user_id,
             etablissement_id, etablissement_label, site_web, statut)
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,'Actif')
        """, (
            row.get("numero_etab"),
            row.get("sr"),
            row.get("discipline"),
            denomination,
            row.get("denomination_ar"),
            code_lr,
            row.get("directeur_nom"),
            row.get("directeur_email"),
            directeur_user_id,
            etablissement_id,
            row.get("etablissement_label"),
            row.get("site_web")
        ))
        conn.commit()
        print(f"✅ Labo inséré : {denomination} ({code_lr})")

    except Exception as e:
        print(f"❌ Erreur insertion labo {row.get('denomination')}: {e}")
        conn.rollback()

print("🚀 Import terminé avec sécurités (doublons et None)")
