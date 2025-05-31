## 📖 Was ist MD5?

**MD5 (Message Digest Algorithm 5)** wurde 1991 von Ronald Rivest entwickelt und gehört zu den bekanntesten Hashfunktionen. Es erzeugt aus beliebig langen Eingaben eine **128-Bit (16 Byte) Hashsumme**, dargestellt als 32-stelliger Hexadezimalwert.

Beispiel:

```
Input : Feuerwehr
MD5   : 1057d58584beec7a2f2b39e472f32719
```

> Achtung: MD5 gilt heute als kryptografisch unsicher, wird aber weiterhin für Integritätsprüfungen, Dateivergleiche und einfache Prüfzwecke verwendet.

---

## ⚙️ Wie funktioniert MD5?

MD5 funktioniert in 4 Hauptschritten:

### 1. Padding
Die Nachricht wird auf eine Länge erweitert, sodass sie kongruent zu 448 mod 512 ist, dann werden 64 Bit mit der Original-Länge (in Bits) angehängt.

### 2. Initialisierung
Vier 32-Bit-Register werden mit festen Werten initialisiert:

- A = 0x67452301
- B = 0xefcdab89
- C = 0x98badcfe
- D = 0x10325476

### 3. Verarbeitung in 64-Runden
- Nachricht wird in 512-Bit-Blöcke unterteilt.
- Jeder Block wird in 16 × 32-Bit-Wörter zerlegt.
- 64 Iterationen mit den vier Funktionen F, G, H, I:
  - F = (B & C) | (~B & D)
  - G = (D & B) | (~D & C)
  - H = B ^ C ^ D
  - I = C ^ (B | ~D)

### 4. Digest-Ausgabe
Die finalen Werte A, B, C, D werden miteinander verknüpft, um die 128-Bit-Hashsumme zu erzeugen.

---

## 🛠 Besondere Merkmale dieser Version

Diese Implementierung:
- ❌ Verwendet **nicht** `md5()` oder `hash()`
- ❌ Verwendet **nicht** `unpack()`
- ✅ Arbeitet mit **manueller Byte-zu-32bit-Konvertierung**
- ✅ Liefert **identische Hashes wie `md5()` in PHP**

---

## 📦 Beispielverwendung

```php
require_once 'md5_manual.php';

$hasher = new MD5Hasher();
$text = "Feuerwehr";
$hash = $hasher->hash($text);

echo "Text: $text\n";
echo "MD5 : $hash\n";
```

---

## 🔎 Vergleich mit eingebautem PHP-Hash

```php
$php = md5("Feuerwehr");               // PHP-intern
$own = $hasher->hash("Feuerwehr");     // Eigene Klasse
```

Beide liefern:
```
1057d58584beec7a2f2b39e472f32719
```

---

## 📁 Dateien

- `md5_manual.php`: Die vollständige Implementierung in PHP ohne Hilfsfunktionen
- `example.php`: Beispielcode zur Verwendung

---

## 🧠 Warum selbst bauen?

- Lerneffekt: Verstehen, wie Hashing intern funktioniert
- Abhängigkeiten vermeiden
- Debuggen und Visualisieren von Hash-Prozessen

---

## ⚠️ Sicherheitshinweis

MD5 ist veraltet und **nicht** mehr sicher für Passwörter oder kryptographische Anwendungen. Verwende SHA-256 oder besser für sicherheitskritische Zwecke.

