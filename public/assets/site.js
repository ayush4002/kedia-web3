/*!
 * Kedia Builders — "The Jaipur Blueprint" site script.
 * Progressive enhancement only: every page works without it.
 */
(function () {
  'use strict';

  const doc = document.documentElement;
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const finePointer = window.matchMedia('(pointer: fine)').matches;
  const t = (key) => (window.KEDIA_I18N && window.KEDIA_I18N[key]) || key;
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
  const el = (tag, className, text) => {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (text !== undefined && text !== null) node.textContent = text;
    return node;
  };
  const sameSite = (url) => (typeof url === 'string' && (url.startsWith('/') || url.startsWith(location.origin))) ? url : '#';

  /* ------------------------------------------------------------ storage */
  const store = {
    read(key) { try { return JSON.parse(localStorage.getItem(key) || '[]'); } catch (e) { return []; } },
    write(key, value) { try { localStorage.setItem(key, JSON.stringify(value)); } catch (e) { /* private mode */ } },
  };
  const KEYS = { wish: 'kedia_wishlist', compare: 'kedia_compare', recent: 'kedia_recent' };

  /* ------------------------------------------------------------ toast */
  function toast(message) {
    let host = $('#toastHost');
    if (!host) {
      host = el('div', 'fixed bottom-24 left-1/2 z-[80] flex -translate-x-1/2 flex-col items-center gap-2 no-print');
      host.id = 'toastHost';
      host.setAttribute('role', 'status');
      host.setAttribute('aria-live', 'polite');
      document.body.appendChild(host);
    }
    const node = el('div', 'rounded-full bg-navy px-5 py-2.5 text-xs font-medium text-paper shadow-xl ring-1 ring-brass/40', message);
    host.appendChild(node);
    setTimeout(() => node.remove(), 3200);
  }

  /* ------------------------------------------------------------ modals & drawers */
  let lastFocus = null;
  function openPanel(id) {
    const panel = document.getElementById(id);
    if (!panel) return;
    lastFocus = document.activeElement;
    panel.hidden = false;
    panel.setAttribute('aria-hidden', 'false');
    requestAnimationFrame(() => panel.classList.add('is-open'));
    document.body.style.overflow = 'hidden';
    if (window.__lenis) window.__lenis.stop();
    const focusable = panel.querySelector('[autofocus], input, select, textarea, button');
    if (focusable) setTimeout(() => focusable.focus(), 60);
    if (id === 'wishlistDrawer') renderWishlist();
  }
  function closePanel(panel) {
    if (!panel) return;
    panel.classList.remove('is-open');
    panel.setAttribute('aria-hidden', 'true');
    setTimeout(() => { panel.hidden = true; }, 250);
    document.body.style.overflow = '';
    if (window.__lenis) window.__lenis.start();
    if (lastFocus) lastFocus.focus();
  }
  document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-open]');
    if (opener) { e.preventDefault(); openPanel(opener.dataset.open); return; }
    const closer = e.target.closest('[data-close]');
    if (closer) { e.preventDefault(); closePanel(closer.closest('[data-panel]')); return; }
    if (e.target.matches('[data-panel]')) closePanel(e.target); // click on backdrop
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') $$('[data-panel].is-open').forEach(closePanel);
    if (e.key === '/' && !/input|textarea|select/i.test(document.activeElement.tagName)) { e.preventDefault(); openPanel('searchPanel'); }
  });
  window.Kedia = { openPanel, closePanel, toast, t };

  /* ------------------------------------------------------------ wishlist */
  function wishlist() { return store.read(KEYS.wish); }
  function isWished(id) { return wishlist().some((x) => x.id === id); }
  function toggleWish(item) {
    let list = wishlist();
    if (list.some((x) => x.id === item.id)) {
      list = list.filter((x) => x.id !== item.id);
      toast(t('Removed from your shortlist'));
    } else {
      list.push(item);
      toast(t('Saved to your shortlist'));
    }
    store.write(KEYS.wish, list);
    syncCounts();
  }
  function renderWishlist() {
    const box = $('#wishlistItems');
    if (!box) return;
    const list = wishlist();
    box.replaceChildren();
    if (!list.length) {
      box.appendChild(el('p', 'py-16 text-center text-sm text-ink/50', t('Your shortlist is empty. Tap the heart on any project or unit to save it here.')));
      return;
    }
    list.forEach((item) => {
      const row = el('div', 'flex items-center gap-4 rounded-2xl border border-navy/10 bg-paper-50 p-3');
      if (item.img) {
        const img = el('img', 'h-16 w-16 flex-none rounded-xl object-cover');
        img.src = sameSite(item.img); img.alt = ''; img.loading = 'lazy';
        row.appendChild(img);
      }
      const info = el('div', 'min-w-0 flex-1');
      info.appendChild(el('div', 'label text-brass-700', item.type === 'unit' ? t('Unit') : t('Project')));
      info.appendChild(el('div', 'truncate font-serif text-lg text-navy', item.title));
      info.appendChild(el('div', 'truncate text-xs text-ink/60', [item.location, item.price].filter(Boolean).join(' · ')));
      const view = el('a', 'btn-navy btn-sm', t('View'));
      view.href = sameSite(item.url);
      const remove = el('button', 'h-9 w-9 flex-none rounded-full text-lg text-ink/50 hover:text-terracotta', '×');
      remove.type = 'button';
      remove.setAttribute('aria-label', t('Remove'));
      remove.addEventListener('click', () => { toggleWish(item); renderWishlist(); syncButtons(); });
      row.append(info, view, remove);
      box.appendChild(row);
    });
  }

  /* ------------------------------------------------------------ compare (units, max 3) */
  function compareList() { return store.read(KEYS.compare); }
  function toggleCompare(item) {
    let list = compareList();
    if (list.some((x) => x.id === item.id)) {
      list = list.filter((x) => x.id !== item.id);
    } else {
      if (list.length >= 3) { toast(t('You can compare up to 3 units')); return; }
      list.push(item);
    }
    store.write(KEYS.compare, list);
    syncCounts();
    renderCompareTray();
  }
  function renderCompareTray() {
    const tray = $('#compareTray');
    if (!tray) return;
    const list = compareList();
    tray.hidden = list.length === 0;
    document.body.classList.toggle('has-compare-tray', !tray.hidden);
    const slots = $('#compareSlots');
    slots.replaceChildren();
    list.forEach((item) => {
      const chip = el('div', 'flex items-center gap-2 rounded-full bg-white/10 py-1.5 pl-4 pr-1.5 text-xs text-paper');
      chip.appendChild(el('span', 'max-w-[10rem] truncate', item.title));
      const x = el('button', 'h-6 w-6 rounded-full text-paper/60 hover:bg-white/10 hover:text-paper', '×');
      x.type = 'button';
      x.setAttribute('aria-label', t('Remove'));
      x.addEventListener('click', () => { toggleCompare(item); syncButtons(); });
      chip.appendChild(x);
      slots.appendChild(chip);
    });
    for (let i = list.length; i < 3; i++) slots.appendChild(el('div', 'hidden rounded-full border border-dashed border-paper/20 px-4 py-1.5 text-xs text-paper/40 sm:block', t('Add a unit')));
    const go = $('#compareGo');
    go.href = '/compare?units=' + list.map((x) => encodeURIComponent(x.id)).join(',');
    go.classList.toggle('pointer-events-none', list.length < 2);
    go.classList.toggle('opacity-50', list.length < 2);
  }

  function syncCounts() {
    const w = wishlist().length, c = compareList().length;
    $$('[data-wishlist-count]').forEach((n) => { n.textContent = w; n.hidden = w === 0; });
    $$('[data-compare-count]').forEach((n) => { n.textContent = c; n.hidden = c === 0; });
  }
  function syncButtons() {
    $$('[data-wishlist]').forEach((btn) => {
      const on = isWished(JSON.parse(btn.dataset.wishlist).id);
      btn.setAttribute('aria-pressed', on ? 'true' : 'false');
      btn.classList.toggle('is-on', on);
    });
    const ids = compareList().map((x) => x.id);
    $$('[data-compare]').forEach((btn) => {
      const on = ids.includes(JSON.parse(btn.dataset.compare).id);
      btn.setAttribute('aria-pressed', on ? 'true' : 'false');
      btn.classList.toggle('is-on', on);
    });
  }
  document.addEventListener('click', (e) => {
    const w = e.target.closest('[data-wishlist]');
    if (w) { e.preventDefault(); toggleWish(JSON.parse(w.dataset.wishlist)); syncButtons(); return; }
    const c = e.target.closest('[data-compare]');
    if (c) { e.preventDefault(); toggleCompare(JSON.parse(c.dataset.compare)); syncButtons(); }
  });

  /* ------------------------------------------------------------ recently viewed */
  function trackRecent() {
    const node = $('[data-recent]');
    if (!node) return;
    const item = JSON.parse(node.dataset.recent);
    const list = store.read(KEYS.recent).filter((x) => x.id !== item.id);
    list.unshift(item);
    store.write(KEYS.recent, list.slice(0, 8));
  }
  function renderRecent() {
    $$('[data-recent-list]').forEach((box) => {
      const current = $('[data-recent]') ? JSON.parse($('[data-recent]').dataset.recent).id : null;
      const list = store.read(KEYS.recent).filter((x) => x.id !== current).slice(0, 4);
      const section = box.closest('[data-recent-section]');
      if (section) section.hidden = list.length === 0;
      box.replaceChildren();
      list.forEach((item) => {
        const a = el('a', 'group flex items-center gap-3 rounded-2xl border border-navy/10 bg-paper-50 p-3 transition hover:border-brass');
        a.href = sameSite(item.url);
        if (item.img) {
          const img = el('img', 'h-14 w-14 flex-none rounded-xl object-cover');
          img.src = sameSite(item.img); img.alt = ''; img.loading = 'lazy';
          a.appendChild(img);
        }
        const info = el('div', 'min-w-0');
        info.appendChild(el('div', 'truncate font-serif text-base text-navy group-hover:text-terracotta', item.title));
        info.appendChild(el('div', 'truncate text-xs text-ink/50', [item.location, item.price].filter(Boolean).join(' · ')));
        a.appendChild(info);
        box.appendChild(a);
      });
    });
  }

  /* ------------------------------------------------------------ global search */
  let searchTimer = null;
  function wireSearch() {
    const input = $('#globalSearchInput');
    const results = $('#globalSearchResults');
    if (!input || !results) return;
    input.addEventListener('input', () => {
      clearTimeout(searchTimer);
      const q = input.value.trim();
      if (q.length < 2) { results.replaceChildren(); return; }
      searchTimer = setTimeout(async () => {
        try {
          const res = await fetch('/api/search?q=' + encodeURIComponent(q), { headers: { Accept: 'application/json' } });
          const data = await res.json();
          results.replaceChildren();
          const groups = [[t('Projects'), data.projects || []], [t('Units'), data.units || []]];
          let any = false;
          groups.forEach(([label, items]) => {
            if (!items.length) return;
            any = true;
            results.appendChild(el('div', 'label px-2 pb-1 pt-4 text-paper/40', label));
            items.forEach((item) => {
              const a = el('a', 'flex items-center justify-between gap-4 rounded-xl px-3 py-2.5 hover:bg-white/5');
              a.href = sameSite(item.url);
              const left = el('div', 'min-w-0');
              left.appendChild(el('div', 'truncate text-paper', item.title));
              left.appendChild(el('div', 'truncate text-xs text-paper/50', item.subtitle || ''));
              a.append(left, el('span', 'label flex-none text-brass', item.meta || ''));
              results.appendChild(a);
            });
          });
          if (!any) results.appendChild(el('p', 'px-3 py-6 text-sm text-paper/50', t('No matches. Try a project name, location or unit code.')));
        } catch (err) { /* offline: the form still submits to /projects */ }
      }, 180);
    });
  }

  /* ------------------------------------------------------------ AI assistant */
  function wireAssistant() {
    const form = $('#assistantForm');
    if (!form) return;
    const out = $('#assistantResults');
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const params = new URLSearchParams(new FormData(form));
      out.replaceChildren(el('p', 'text-sm text-paper/60', t('Finding the best matches…')));
      try {
        const res = await fetch('/api/recommend?' + params.toString(), { headers: { Accept: 'application/json' } });
        const data = await res.json();
        out.replaceChildren();
        out.appendChild(el('p', 'mb-3 text-sm text-paper/80', data.summary || ''));
        (data.units || []).forEach((u) => {
          const a = el('a', 'mb-2 flex items-center justify-between gap-3 rounded-xl border border-paper/10 px-4 py-3 hover:border-brass');
          a.href = sameSite(u.url);
          const left = el('div', 'min-w-0');
          left.appendChild(el('div', 'truncate text-sm text-paper', u.title));
          left.appendChild(el('div', 'truncate text-xs text-paper/50', u.subtitle));
          a.append(left, el('span', 'flex-none font-mono text-xs text-brass', u.price));
          out.appendChild(a);
        });
        if (data.browse_url) {
          const more = el('a', 'btn-brass btn-sm mt-2', t('See all matches'));
          more.href = sameSite(data.browse_url);
          out.appendChild(more);
        }
      } catch (err) {
        out.replaceChildren(el('p', 'text-sm text-paper/60', t('Something went wrong. Please try again.')));
      }
    });
  }

  /* ------------------------------------------------------------ motion */
  function revealAll() { $$('[data-reveal]').forEach((n) => n.classList.add('is-in')); }

  function wireReveal() {
    if (reduceMotion || !('IntersectionObserver' in window)) { revealAll(); return; }
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const node = entry.target;
        const delay = parseInt(node.dataset.revealDelay || '0', 10);
        setTimeout(() => node.classList.add('is-in'), delay);
        io.unobserve(node);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    $$('[data-reveal]').forEach((n) => io.observe(n));
    setTimeout(revealAll, 4000); // never leave content hidden
  }

  function wireDraw() {
    const drawings = $$('[data-draw]');
    drawings.forEach((svg) => {
      svg.querySelectorAll('path, line, rect, circle, polyline').forEach((shape) => {
        try { shape.style.setProperty('--len', Math.ceil(shape.getTotalLength())); } catch (e) { /* not rendered */ }
      });
    });
    if (reduceMotion || !('IntersectionObserver' in window)) { drawings.forEach((s) => s.classList.add('is-drawn')); return; }
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) { entry.target.classList.add('is-drawn'); io.unobserve(entry.target); }
      });
    }, { threshold: 0.2 });
    drawings.forEach((s) => io.observe(s));
  }

  function wireCounters() {
    const fmt = (n, plain) => plain ? String(n) : n.toLocaleString('en-IN');
    const run = (node) => {
      const target = parseFloat(node.dataset.count);
      const plain = node.dataset.plain === 'true';
      const suffix = node.dataset.suffix || '';
      if (reduceMotion) { node.textContent = fmt(target, plain) + suffix; return; }
      const from = plain ? Math.max(0, target - 60) : 0;
      const start = performance.now();
      const dur = 1800;
      const step = (now) => {
        const p = Math.min(1, (now - start) / dur);
        const eased = 1 - Math.pow(1 - p, 3);
        node.textContent = fmt(Math.round(from + (target - from) * eased), plain) + suffix;
        if (p < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    };
    const nodes = $$('[data-count]');
    if (!('IntersectionObserver' in window)) { nodes.forEach(run); return; }
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => { if (entry.isIntersecting) { run(entry.target); io.unobserve(entry.target); } });
    }, { threshold: 0.4 });
    nodes.forEach((n) => io.observe(n));
  }

  function wireHaveli() {
    const hero = $('[data-haveli]');
    if (!hero) return;
    if (reduceMotion) { hero.classList.add('is-open'); return; }
    setTimeout(() => hero.classList.add('is-open'), 350);
  }

  function wireCursor() {
    if (!finePointer || reduceMotion) return;
    const ring = el('div', 'cursor-ring');
    ring.setAttribute('aria-hidden', 'true');
    document.body.appendChild(ring);
    let x = 0, y = 0, rx = 0, ry = 0;
    window.addEventListener('mousemove', (e) => { x = e.clientX; y = e.clientY; ring.classList.add('is-active'); }, { passive: true });
    document.addEventListener('mouseleave', () => ring.classList.remove('is-active'));
    document.addEventListener('mouseover', (e) => {
      ring.classList.toggle('is-hover', !!e.target.closest('a, button, [role="button"], input, select, textarea, label'));
    });
    const loop = () => {
      rx += (x - rx) * 0.2; ry += (y - ry) * 0.2;
      ring.style.transform = `translate3d(${rx}px, ${ry}px, 0)`;
      requestAnimationFrame(loop);
    };
    loop();
  }

  function wireSmoothScroll() {
    if (reduceMotion || typeof window.Lenis !== 'function') return;
    const lenis = new window.Lenis({ duration: 1.1, smoothWheel: true });
    window.__lenis = lenis;
    if (window.gsap && window.ScrollTrigger) {
      window.gsap.registerPlugin(window.ScrollTrigger);
      lenis.on('scroll', window.ScrollTrigger.update);
      window.gsap.ticker.add((time) => lenis.raf(time * 1000));
      window.gsap.ticker.lagSmoothing(0);
      $$('[data-parallax]').forEach((node) => {
        const speed = parseFloat(node.dataset.parallax) || 0.15;
        window.gsap.to(node, {
          yPercent: speed * 100, ease: 'none',
          scrollTrigger: { trigger: node.closest('section') || node, start: 'top bottom', end: 'bottom top', scrub: true },
        });
      });
    } else {
      const raf = (time) => { lenis.raf(time); requestAnimationFrame(raf); };
      requestAnimationFrame(raf);
    }
    // Anchor links scroll through Lenis
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a[href^="#"]');
      if (!a || a.getAttribute('href').length < 2) return;
      const target = document.querySelector(a.getAttribute('href'));
      if (target) { e.preventDefault(); lenis.scrollTo(target, { offset: -90 }); }
    });
  }

  function wireHeader() {
    const header = $('#siteHeader');
    if (!header) return;
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 24);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ------------------------------------------------------------ tabs (generic) */
  function wireTabs() {
    $$('[data-tabs]').forEach((group) => {
      const buttons = $$('[role="tab"]', group);
      buttons.forEach((btn) => btn.addEventListener('click', () => {
        buttons.forEach((b) => {
          const on = b === btn;
          b.setAttribute('aria-selected', on ? 'true' : 'false');
          b.classList.toggle('is-active', on);
          const panel = document.getElementById(b.getAttribute('aria-controls'));
          if (panel) panel.hidden = !on;
        });
      }));
    });
  }

  /* ------------------------------------------------------------ lightbox */
  function wireLightbox() {
    const box = $('#lightbox');
    if (!box) return;
    const stage = $('#lightboxStage');
    const caption = $('#lightboxCaption');
    let items = [], index = 0;
    const show = (i) => {
      index = (i + items.length) % items.length;
      const item = items[index];
      stage.replaceChildren();
      if (item.dataset.type === 'video') {
        const v = el('video', 'max-h-[78vh] w-auto max-w-full rounded-2xl');
        v.src = sameSite(item.dataset.src); v.controls = true; v.autoplay = true; v.playsInline = true;
        stage.appendChild(v);
      } else {
        const img = el('img', 'max-h-[78vh] w-auto max-w-full rounded-2xl object-contain');
        img.src = sameSite(item.dataset.src); img.alt = item.dataset.caption || '';
        stage.appendChild(img);
      }
      caption.textContent = item.dataset.caption || '';
    };
    document.addEventListener('click', (e) => {
      const item = e.target.closest('[data-lightbox]');
      if (!item) return;
      e.preventDefault();
      items = $$('[data-lightbox]').filter((n) => !n.closest('[hidden]'));
      openPanel('lightbox');
      show(items.indexOf(item));
    });
    $('#lightboxPrev')?.addEventListener('click', () => show(index - 1));
    $('#lightboxNext')?.addEventListener('click', () => show(index + 1));
    document.addEventListener('keydown', (e) => {
      if (box.hidden) return;
      if (e.key === 'ArrowLeft') show(index - 1);
      if (e.key === 'ArrowRight') show(index + 1);
    });
  }

  /* ------------------------------------------------------------ boot */
  function boot() {
    syncCounts();
    syncButtons();
    renderCompareTray();
    trackRecent();
    renderRecent();
    wireSearch();
    wireAssistant();
    wireReveal();
    wireDraw();
    wireCounters();
    wireHaveli();
    wireCursor();
    wireSmoothScroll();
    wireHeader();
    wireTabs();
    wireLightbox();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
