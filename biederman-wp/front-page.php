<?php
/**
 * Front Page template (one-page site).
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$tagline = get_theme_mod('biederman_tagline', 'Die witzigste generationsübergreifende Band');
$lead    = get_theme_mod('biederman_lead', 'Live-Shows, Songs und Geschichten zwischen Generationen – mit Humor, Haltung und Herz.');

$event_title = get_theme_mod('biederman_event_title', 'ReleaseParty');
$event_loc   = get_theme_mod('biederman_event_location', 'Loop, Gürtelbogen 26, 1080 Wien');

$booking_email = get_theme_mod('biederman_booking_email', 'booking@biederman.band');

$instagram = get_theme_mod('biederman_instagram', '');
$youtube   = get_theme_mod('biederman_youtube', '');
$tiktok    = get_theme_mod('biederman_tiktok', '');
$facebook  = get_theme_mod('biederman_facebook', '');
?>

<main id="main">
  <section class="hero" aria-label="Hero">
    <div class="container hero__grid">
      <div class="hero__copy">
        <p class="kicker"><?php echo esc_html($tagline); ?></p>
        <h1><?php echo esc_html(get_bloginfo('name', 'display')); ?></h1>
        <p class="lead"><?php echo esc_html($lead); ?></p>

        <div class="hero__actions">
          <a class="button primary" href="#shows"><?php esc_html_e('Nächster Gig', 'biederman'); ?></a>
          <a class="button" href="#media"><?php esc_html_e('Reinhören', 'biederman'); ?></a>
        </div>

        <ul class="chips" aria-label="Kurzinfo">
          <li>Wien</li>
          <li>Live &amp; Comedy</li>
          <li>Booking &amp; Presse</li>
        </ul>
      </div>

      <figure class="hero__art">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/biederman-loop-26-hp.png'); ?>"
             alt="<?php esc_attr_e('Biederman – ReleaseParty Poster', 'biederman'); ?>"
             width="1205" height="556" loading="eager" />
        <figcaption><?php echo esc_html(sprintf('Aktuell: %s @ %s', 'ReleaseParty', 'Loop, Wien')); ?></figcaption>
      </figure>
    </div>
  </section>

  <section id="shows" class="section">
    <div class="container">
      <div class="section__head">
        <h2><?php esc_html_e('Shows', 'biederman'); ?></h2>
        <p><?php esc_html_e('Tickets, Kalender-Export und alle Infos an einem Ort.', 'biederman'); ?></p>
      </div>

      <div class="cards">
        <article class="card card--featured" aria-label="Nächster Gig">
          <div class="card__badge"><?php esc_html_e('Nächster Gig', 'biederman'); ?></div>
          <h3><?php echo esc_html($event_title); ?></h3>

          <p class="meta">
            <strong><?php esc_html_e('Nächster Termin', 'biederman'); ?></strong> · <span><?php echo esc_html($event_loc); ?></span>
          </p>

          <div class="card__actions">
            <a class="button primary" href="<?php echo esc_url('https://maps.google.com/?q=' . rawurlencode($event_loc)); ?>" target="_blank" rel="noreferrer">
              <?php esc_html_e('Route', 'biederman'); ?>
            </a>
            <button class="button" id="btn-ics" type="button"><?php esc_html_e('In Kalender', 'biederman'); ?></button>
            <button class="button" id="btn-copy" type="button"><?php esc_html_e('Adresse kopieren', 'biederman'); ?></button>
          </div>

          <p class="small muted" id="show-msg" role="status" aria-live="polite"></p>
        </article>

        <article class="card" aria-label="Weitere Termine">
          <h3><?php esc_html_e('Weitere Termine', 'biederman'); ?></h3>
          <p class="muted"><?php esc_html_e('Hier kommen die nächsten Dates rein.', 'biederman'); ?></p>
          <ul class="list">
            <li><span class="pill">TBA</span> Frühjahr 2026 · Wien &amp; Umgebung</li>
            <li><span class="pill">TBA</span> Sommer 2026 · Festivals</li>
          </ul>
          <a class="textlink" href="#contact"><?php esc_html_e('Ihr wollt uns buchen? →', 'biederman'); ?></a>
        </article>
      </div>
    </div>
  </section>

  <section id="media" class="section section--alt">
    <div class="container">
      <div class="section__head">
        <h2><?php esc_html_e('Media', 'biederman'); ?></h2>
        <p><?php esc_html_e('Ein Platz für euer neuestes Video, Live-Mitschnitte oder Playlist-Embeds.', 'biederman'); ?></p>
      </div>

      <div class="media">
        <div class="embed">
          <div class="embed__ratio" role="group" aria-label="Video Platzhalter">
            <div class="embed__placeholder">
              <p><strong><?php esc_html_e('Video-Embed', 'biederman'); ?></strong></p>
              <p class="muted"><?php esc_html_e('Ersetzt diesen Block z.B. mit einem YouTube/Vimeo iFrame.', 'biederman'); ?></p>
            </div>
          </div>
        </div>

        <div class="stack">
          <div class="panel">
            <h3><?php esc_html_e('Streaming', 'biederman'); ?></h3>
            <p class="muted"><?php esc_html_e('Links zu Spotify, Apple Music, Bandcamp & Co.', 'biederman'); ?></p>
            <div class="links">
              <a href="#" aria-disabled="true">Spotify</a>
              <a href="#" aria-disabled="true">Apple Music</a>
              <a href="#" aria-disabled="true">Bandcamp</a>
              <a href="#" aria-disabled="true">YouTube</a>
            </div>
            <p class="small muted"><?php esc_html_e('Tipp: Nutzt „Link-in-bio“ nur als Ergänzung — eure Website bleibt der Hub.', 'biederman'); ?></p>
          </div>

          <div class="panel">
            <h3><?php esc_html_e('Newsletter', 'biederman'); ?></h3>
            <p class="muted"><?php esc_html_e('Updates zu Shows, Releases und exklusiven Dingen.', 'biederman'); ?></p>
            <form id="newsletter" class="form" autocomplete="on">
              <label>
                <span class="sr"><?php esc_html_e('E-Mail', 'biederman'); ?></span>
                <input name="email" type="email" required placeholder="deine@email.at" inputmode="email" />
              </label>
              <button class="button primary" type="submit"><?php esc_html_e('Anmelden', 'biederman'); ?></button>
              <p class="small muted">
                <?php esc_html_e('Double-Opt-In empfohlen. Kein Spam.', 'biederman'); ?>
                <a href="#privacy" class="textlink"><?php esc_html_e('Datenschutz', 'biederman'); ?></a>.
              </p>
            </form>
            <p class="small" id="nl-msg" role="status" aria-live="polite"></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="about" class="section">
    <div class="container">
      <div class="section__head">
        <h2><?php esc_html_e('Über uns', 'biederman'); ?></h2>
        <p><?php esc_html_e('Kurzer Pitch + was euch als Band ausmacht (für Fans, Presse & Booker).', 'biederman'); ?></p>
      </div>

      <div class="cols">
        <div>
          <p><strong>Biederman</strong> verbindet Generationen auf der Bühne: Songs, Pointen und Popkultur – mit einem Blick
          nach vorne und einem liebevollen Augenzwinkern zurück.</p>
          <p class="muted"><?php esc_html_e('Diese Texte sind Platzhalter. Tauscht sie gegen euren echten Band-Text aus.', 'biederman'); ?></p>
        </div>

        <div class="facts" aria-label="Facts">
          <div class="fact"><div class="fact__k">Stil</div><div class="fact__v">Comedy · Live · Pop</div></div>
          <div class="fact"><div class="fact__k">Base</div><div class="fact__v">Wien</div></div>
          <div class="fact"><div class="fact__k">Buchung</div><div class="fact__v"><a class="textlink" href="#contact">Kontakt →</a></div></div>
          <div class="fact"><div class="fact__k">Technik</div><div class="fact__v">Rider / Inputs auf Anfrage</div></div>
        </div>
      </div>
    </div>
  </section>

  <section id="press" class="section section--alt">
    <div class="container">
      <div class="section__head">
        <h2><?php esc_html_e('Presse & EPK', 'biederman'); ?></h2>
        <p><?php esc_html_e('Alles, was Medien & Veranstalter schnell brauchen.', 'biederman'); ?></p>
      </div>

      <div class="cards">
        <article class="card">
          <h3><?php esc_html_e('Pressetext (kurz)', 'biederman'); ?></h3>
          <p class="muted"><?php esc_html_e('Ein Absatz, der sofort erklärt, wer ihr seid, wie es klingt/ist und warum das Publikum kommen sollte.', 'biederman'); ?></p>
          <button class="button" id="btn-copy-press" type="button"><?php esc_html_e('Text kopieren', 'biederman'); ?></button>
          <p class="small muted" id="press-msg" role="status" aria-live="polite"></p>
          <textarea id="press-text" class="sr" aria-hidden="true">Biederman ist die witzigste generationsübergreifende Band: Live, laut, liebevoll – mit Songs und Schmäh, die verbinden.</textarea>
        </article>

        <article class="card">
          <h3><?php esc_html_e('Downloads', 'biederman'); ?></h3>
          <ul class="list">
            <li><span class="pill">TBA</span> Pressefotos (ZIP)</li>
            <li><span class="pill">TBA</span> Stage-Rider (PDF)</li>
            <li><span class="pill">TBA</span> Logo-Pack (SVG/PNG)</li>
          </ul>
          <p class="small muted"><?php esc_html_e('Legt die Dateien später einfach in den Theme-Ordner (oder Medienbibliothek) und verlinkt sie hier.', 'biederman'); ?></p>
        </article>
      </div>
    </div>
  </section>

  <section id="contact" class="section">
    <div class="container">
      <div class="section__head">
        <h2><?php esc_html_e('Booking & Kontakt', 'biederman'); ?></h2>
        <p><?php esc_html_e('Schnellster Weg: Mail. Alternativ Kontaktformular (mit Backend).', 'biederman'); ?></p>
      </div>

      <div class="contact">
        <div class="panel">
          <h3><?php esc_html_e('Direkt', 'biederman'); ?></h3>
          <p class="muted"><?php esc_html_e('Ersetzt die Platzhalter-Adresse mit eurer echten Booking-Mail (Customizer).', 'biederman'); ?></p>
          <div class="contact__row">
            <a class="button primary" id="booking-email" data-email="<?php echo esc_attr($booking_email); ?>"
               href="<?php echo esc_url('mailto:' . $booking_email . '?subject=' . rawurlencode('Booking Anfrage – Biederman')); ?>">
              <?php echo esc_html($booking_email); ?>
            </a>
            <button class="button" id="btn-copy-mail" type="button"><?php esc_html_e('Mail kopieren', 'biederman'); ?></button>
          </div>
          <p class="small muted" id="mail-msg" role="status" aria-live="polite"></p>

          <div class="links links--social" aria-label="Social Links">
            <?php if ($instagram): ?><a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noreferrer">Instagram</a><?php endif; ?>
            <?php if ($tiktok): ?><a href="<?php echo esc_url($tiktok); ?>" target="_blank" rel="noreferrer">TikTok</a><?php endif; ?>
            <?php if ($youtube): ?><a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noreferrer">YouTube</a><?php endif; ?>
            <?php if ($facebook): ?><a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noreferrer">Facebook</a><?php endif; ?>
            <?php if (!$instagram && !$tiktok && !$youtube && !$facebook): ?>
              <a href="#" aria-disabled="true">Instagram</a>
              <a href="#" aria-disabled="true">TikTok</a>
              <a href="#" aria-disabled="true">YouTube</a>
              <a href="#" aria-disabled="true">Facebook</a>
            <?php endif; ?>
          </div>
        </div>

        <div class="panel" id="privacy">
          <h3><?php esc_html_e('Datenschutz (Kurz)', 'biederman'); ?></h3>
          <p class="muted"><?php esc_html_e('Wenn ihr ein Newsletter-Tool oder Analytics nutzt: informiert transparent, holt Einwilligungen ein und verlinkt eine vollständige Datenschutzerklärung.', 'biederman'); ?></p>
          <details>
            <summary><?php esc_html_e('Mini-Checkliste', 'biederman'); ?></summary>
            <ul class="list">
              <li><?php esc_html_e('Impressum & Kontakt', 'biederman'); ?></li>
              <li><?php esc_html_e('Datenschutzerklärung (Newsletter/Tracking/Embeds)', 'biederman'); ?></li>
              <li><?php esc_html_e('Cookie-/Consent-Banner, falls nicht-essenzielle Cookies gesetzt werden', 'biederman'); ?></li>
            </ul>
          </details>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
