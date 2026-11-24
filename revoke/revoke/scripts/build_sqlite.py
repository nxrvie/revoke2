#!/usr/bin/env python3
"""Convertit cybershield/database.sql (MySQL) en database.sqlite autonome.

Utile pour un déploiement sans serveur MySQL (Render, démo locale, tests).
Usage : python3 scripts/build_sqlite.py
"""
import pathlib
import re
import sqlite3

ROOT = pathlib.Path(__file__).resolve().parent.parent
src = (ROOT / "cybershield" / "database.sql").read_text(encoding="utf-8")

# Instructions spécifiques MySQL / XAMPP inutiles pour SQLite
src = re.sub(r"CREATE DATABASE[\s\S]*?;", "", src, flags=re.I)
src = re.sub(r"USE\s+cybershield_db\s*;", "", src, flags=re.I)
src = re.sub(r"SET\s+FOREIGN_KEY_CHECKS\s*=\s*\d+\s*;", "", src, flags=re.I)

# Types et options
src = re.sub(r"ENGINE=InnoDB\s+DEFAULT\s+CHARSET=utf8mb4\s*;", ";", src, flags=re.I)
src = re.sub(r"\bINT\s+AUTO_INCREMENT\s+PRIMARY\s+KEY\b", "INTEGER PRIMARY KEY AUTOINCREMENT", src, flags=re.I)
src = re.sub(r"\bTINYINT\s*\(\s*\d+\s*\)", "INTEGER", src, flags=re.I)
src = re.sub(r"\bDECIMAL\s*\(\s*\d+\s*,\s*\d+\s*\)", "NUMERIC", src, flags=re.I)
src = re.sub(r"ENUM\s*\((?:'[^']*'(?:\s*,\s*)?)*\)", "TEXT", src, flags=re.I)
src = re.sub(r"\bUNIQUE\s+KEY\s+\w+\s*\(([^)]*)\)", r"UNIQUE (\1)", src, flags=re.I)
src = re.sub(r"\bINT\b", "INTEGER", src)

# Découpage en instructions (en ignorant les points-virgules dans les chaînes)
statements, buf, in_str, i = [], [], False, 0
while i < len(src):
    c = src[i]
    if c == "'":
        if in_str and i + 1 < len(src) and src[i + 1] == "'":
            buf.append("''")
            i += 2
            continue
        in_str = not in_str
    if c == ";" and not in_str:
        statements.append("".join(buf).strip())
        buf = []
    else:
        buf.append(c)
    i += 1
if "".join(buf).strip():
    statements.append("".join(buf).strip())

out = ROOT / "cybershield" / "database.sqlite"
if out.exists():
    out.unlink()
conn = sqlite3.connect(out)
conn.executescript("PRAGMA foreign_keys = OFF;\n" + "\n".join(s + ";" for s in statements if s))
conn.commit()

counts = {t: conn.execute(f"SELECT COUNT(*) FROM {t}").fetchone()[0]
          for (t,) in conn.execute("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")}
conn.close()
print("Base SQLite créée :", out)
print("Tables :", counts)
