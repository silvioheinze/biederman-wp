const $ = (sel, root=document) => root.querySelector(sel);

function toast(el, msg){
  if(!el) return;
  el.textContent = msg;
  clearTimeout(el._t);
  el._t = setTimeout(() => { el.textContent = ""; }, 3500);
}

function download(filename, content, type="text/plain;charset=utf-8"){
  const blob = new Blob([content], {type});
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  a.remove();
  URL.revokeObjectURL(url);
}

/** Minimal ICS generator for a single event */
function makeICS({title, start, durationMinutes, location, description, url}){
  const pad = (n) => String(n).padStart(2,"0");
  const dt = (d) => (
    d.getFullYear() +
    pad(d.getMonth()+1) +
    pad(d.getDate()) + "T" +
    pad(d.getHours()) +
    pad(d.getMinutes()) +
    "00"
  );
  const uid = "biederman-" + dt(start) + "@biederman.band";
  const end = new Date(start.getTime() + durationMinutes*60*1000);

  const esc = (s) => String(s)
    .replace(/\\/g,"\\\\")
    .replace(/\n/g,"\\n")
    .replace(/,/g,"\\,")
    .replace(/;/g,"\\;");

  return [
    "BEGIN:VCALENDAR",
    "VERSION:2.0",
    "PRODID:-//Biederman//Website//DE",
    "CALSCALE:GREGORIAN",
    "METHOD:PUBLISH",
    "BEGIN:VEVENT",
    `UID:${uid}`,
    `DTSTAMP:${dt(new Date())}`,
    `DTSTART:${dt(start)}`,
    `DTEND:${dt(end)}`,
    `SUMMARY:${esc(title)}`,
    `LOCATION:${esc(location)}`,
    `DESCRIPTION:${esc(description)}\\n${esc(url)}`,
    "END:VEVENT",
    "END:VCALENDAR"
  ].join("\r\n");
}

function initNav(){
  const btn = $(".navbtn");
  const nav = $("#nav");
  if(!btn || !nav) return;

  btn.addEventListener("click", () => {
    const open = nav.dataset.open === "true";
    nav.dataset.open = String(!open);
    btn.setAttribute("aria-expanded", String(!open));
  });

  nav.addEventListener("click", (e) => {
    const a = e.target.closest("a");
    if(!a) return;
    nav.dataset.open = "false";
    btn.setAttribute("aria-expanded", "false");
  });

  document.addEventListener("click", (e) => {
    if(nav.contains(e.target) || btn.contains(e.target)) return;
    nav.dataset.open = "false";
    btn.setAttribute("aria-expanded", "false");
  });
}

function getEvent(){
  // Provided by WordPress via wp_localize_script in functions.php
  const fallback = {
    title: "Biederman – ReleaseParty",
    startISO: "2026-02-20T20:00:00+01:00",
    durationMinutes: 150,
    location: "Loop, Gürtelbogen 26, 1080 Wien",
    description: "Biederman live im Loop (Wien).",
    url: (location.origin || "https://example.com") + "/#shows"
  };
  const data = (window.BIEDERMAN_EVENT && typeof window.BIEDERMAN_EVENT === "object")
    ? {...fallback, ...window.BIEDERMAN_EVENT}
    : fallback;
  return data;
}

function initActions(){
  const showMsg = $("#show-msg");
  const btnICS = $("#btn-ics");
  const btnCopy = $("#btn-copy");
  const btnCopyMail = $("#btn-copy-mail");
  const mailMsg = $("#mail-msg");
  const btnCopyPress = $("#btn-copy-press");
  const pressMsg = $("#press-msg");

  const ev = getEvent();
  const venue = ev.location;
  const start = new Date(ev.startISO);

  btnICS?.addEventListener("click", () => {
    const ics = makeICS({
      title: ev.title,
      start,
      durationMinutes: Number(ev.durationMinutes || 150),
      location: venue,
      description: ev.description,
      url: ev.url
    });
    download("biederman-event.ics", ics, "text/calendar;charset=utf-8");
    toast(showMsg, "Kalenderdatei heruntergeladen (.ics).");
  });

  btnCopy?.addEventListener("click", async () => {
    try{
      await navigator.clipboard.writeText(venue);
      toast(showMsg, "Adresse kopiert.");
    }catch{
      toast(showMsg, "Kopieren nicht möglich (Browser-Einstellung).");
    }
  });

  btnCopyMail?.addEventListener("click", async () => {
    const el = $("#booking-email");
    const email = el?.dataset?.email || "booking@biederman.band";
    try{
      await navigator.clipboard.writeText(email);
      toast(mailMsg, "Mailadresse kopiert.");
    }catch{
      toast(mailMsg, "Kopieren nicht möglich (Browser-Einstellung).");
    }
  });

  btnCopyPress?.addEventListener("click", async () => {
    try{
      const text = $("#press-text")?.value?.trim() || "";
      await navigator.clipboard.writeText(text);
      toast(pressMsg, "Pressetext kopiert.");
    }catch{
      toast(pressMsg, "Kopieren nicht möglich (Browser-Einstellung).");
    }
  });
}

function initNewsletter(){
  const form = $("#newsletter");
  const msg = $("#nl-msg");
  if(!form || !msg) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const email = new FormData(form).get("email")?.toString().trim() || "";
    if(!email){
      msg.textContent = "Bitte eine E-Mail eingeben.";
      return;
    }
    msg.textContent = "Danke! Demo-Formular: hier würdet ihr jetzt Double-Opt-In auslösen.";
    form.reset();
  });
}

function initFeaturedShowICS(){
  // Handle ICS export for featured shows
  const btnICSFeatured = document.querySelectorAll('.btn-ics-featured');
  btnICSFeatured.forEach((btn) => {
    btn.addEventListener('click', () => {
      const title = btn.dataset.showTitle || 'Biederman Show';
      const dateStr = btn.dataset.showDate || '';
      const location = btn.dataset.showLocation || '';
      const description = btn.dataset.showDescription || title;
      const url = btn.dataset.showUrl || window.location.href;
      
      // Parse date string (format: YYYY-MM-DD HH:MM:SS or YYYY-MM-DDTHH:MM)
      let startDate;
      if (dateStr) {
        // Try ISO format first (YYYY-MM-DDTHH:MM)
        if (dateStr.includes('T')) {
          startDate = new Date(dateStr);
        } else {
          // Try MySQL datetime format (YYYY-MM-DD HH:MM:SS)
          startDate = new Date(dateStr.replace(' ', 'T'));
        }
      } else {
        startDate = new Date();
      }
      
      // Validate date
      if (isNaN(startDate.getTime())) {
        const msgEl = btn.closest('.card')?.querySelector('.show-ics-msg');
        if (msgEl) {
          toast(msgEl, 'Ungültiges Datum.');
        }
        return;
      }
      
      // Default duration: 150 minutes (2.5 hours)
      const durationMinutes = 150;
      
      const ics = makeICS({
        title: title,
        start: startDate,
        durationMinutes: durationMinutes,
        location: location,
        description: description,
        url: url
      });
      
      // Generate filename from title and date
      const dateStrForFilename = startDate.toISOString().split('T')[0];
      const filename = `biederman-${dateStrForFilename}.ics`;
      
      download(filename, ics, 'text/calendar;charset=utf-8');
      
      const msgEl = btn.closest('.card')?.querySelector('.show-ics-msg');
      if (msgEl) {
        toast(msgEl, 'Kalenderdatei heruntergeladen (.ics).');
      }
    });
  });
}

initNav();
initActions();
initNewsletter();
initFeaturedShowICS();
