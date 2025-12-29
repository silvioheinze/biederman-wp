# Automatische Theme-Updates

Das Biederman Theme unterstützt automatische Updates über GitHub Releases.

## Funktionsweise

1. **GitHub Release erstellen**: Wenn du ein neues Release auf GitHub erstellst, wird automatisch ein Theme-ZIP erstellt und dem Release hinzugefügt.

2. **Update-Prüfung**: WordPress prüft regelmäßig (alle 12 Stunden) auf neue Releases über die GitHub API.

3. **Update-Benachrichtigung**: Wenn eine neuere Version verfügbar ist, erscheint eine Benachrichtigung im WordPress Admin unter **Design → Themes**.

4. **Update installieren**: Du kannst das Update direkt aus dem WordPress Admin installieren.

## Voraussetzungen

- GitHub Repository: `silvioheinze/biederman-wp`
- Release-Tags müssen dem Format `v1.0.0` folgen (mit oder ohne 'v' Präfix)
- Das ZIP-File muss im Release enthalten sein (wird automatisch von GitHub Actions erstellt)

## Release erstellen

1. **Version in `style.css` aktualisieren**:
   ```css
   Version: 1.0.1
   ```

2. **Änderungen committen und pushen**:
   ```bash
   git add .
   git commit -m "Update to version 1.0.1"
   git push origin main
   ```

3. **GitHub Release erstellen**:
   - Gehe zu: https://github.com/silvioheinze/biederman-wp/releases/new
   - Wähle einen Tag: `v1.0.1` (oder erstelle einen neuen Tag)
   - Titel: `Version 1.0.1`
   - Beschreibung: Changelog/Release Notes
   - Klicke auf **"Publish release"**

4. **GitHub Actions**: Die GitHub Action erstellt automatisch das Theme-ZIP und fügt es dem Release hinzu.

## Update-Prüfung

WordPress prüft automatisch auf Updates:
- Beim Laden des Admin-Bereichs
- Alle 12 Stunden automatisch
- Manuell über **Design → Themes** → "Nach Updates suchen"

## Konfiguration

Die Update-Konfiguration befindet sich in `inc/theme-updater.php`:

```php
define('BIEDERMAN_GITHUB_USER', 'silvioheinze');
define('BIEDERMAN_GITHUB_REPO', 'biederman-wp');
```

Falls du das Repository ändern möchtest, passe diese Konstanten an.

## Troubleshooting

### Updates werden nicht angezeigt

1. **Version prüfen**: Stelle sicher, dass die Version im Release höher ist als die aktuelle Theme-Version in `style.css`.

2. **ZIP-Datei prüfen**: Das ZIP muss im Release enthalten sein. Prüfe unter: https://github.com/silvioheinze/biederman-wp/releases/latest

3. **Cache leeren**: 
   - WordPress Update-Cache: `delete_site_transient('update_themes')`
   - Theme-Cache: `delete_transient('biederman_latest_release')`

4. **API-Limit**: GitHub API hat ein Limit von 60 Requests/Stunde ohne Authentifizierung. Bei vielen Installationen könnte das Limit erreicht werden.

### Rate Limit Fehler

Falls du viele Installationen hast, kannst du einen GitHub Personal Access Token verwenden:

1. Erstelle einen Token unter: https://github.com/settings/tokens
2. Füge ihn in `inc/theme-updater.php` hinzu:
   ```php
   'headers' => array(
       'Authorization' => 'token DEIN_TOKEN_HIER',
       'Accept' => 'application/vnd.github.v3+json',
   ),
   ```

## Sicherheit

- Updates werden nur von GitHub Releases installiert
- ZIP-Dateien werden von GitHub bereitgestellt
- Keine zusätzlichen Server oder Dienste erforderlich
- Standard WordPress Update-Mechanismus wird verwendet

