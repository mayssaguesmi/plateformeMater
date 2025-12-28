import pandas as pd
import sys

EXCEL_FILE = "Liste des Labos de recherche UTM.xlsx"

try:
    df = pd.read_excel(EXCEL_FILE)
    print(f"📄 Fichier Excel chargé : {EXCEL_FILE}")
except Exception as e:
    print(f"❌ Erreur lecture Excel : {e}")
    sys.exit(1)

# --- Afficher colonnes brutes ---
print("\n=== Colonnes détectées par pandas ===")
for i, col in enumerate(df.columns):
    print(f"{i}: '{col}'")

# --- Normaliser colonnes ---
df.columns = (
    df.columns
    .astype(str)               # assure string
    .str.strip()               # supprime espaces
    .str.lower()               # minuscule
    .str.replace(r"[^\w\d]", "_", regex=True)  # remplace accents/espaces spéciaux
)

print("\n=== Colonnes Excel normalisées ===")
print(list(df.columns))

# --- Aperçu des données ---
print("\n=== 3 premières lignes ===")
print(df.head(3).to_string(index=False))
