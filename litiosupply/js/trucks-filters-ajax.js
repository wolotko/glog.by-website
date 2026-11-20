(function () {


  // защита от двойной инициализации
  if (window.__TRUCKS_FILTERS_AJAX_INITED__) return;
  window.__TRUCKS_FILTERS_AJAX_INITED__ = true;

  let currentPage = 1;

  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }

  function getTypeLabelFromSelect(value) {
    if (!value) return "";
    const sel = qs("#truck_type");
    if (!sel) return "";

    const opt = Array.from(sel.options).find((o) => (o.value || "").trim() === value);
    if (!opt) return "";

    const lbl = (opt.textContent || "").trim();
    if (!lbl || lbl === "Тип техники") return "";
    return lbl;
  }

  function buildHeadingParts(state) {
    const type = (state.type || "").trim();
    const brand = (state.brand || "").trim();
    const year = (state.year || "").trim();

    const parts = [];

    const typeLabel = getTypeLabelFromSelect(type);
    if (typeLabel) parts.push(typeLabel);

    if (brand) parts.push(brand);

    if (year) parts.push(year + " г.в.");

    return parts;
  }

  function setH1FromState(state) {
    const h1El = qs("section.text-head h1") || qs("h1");
    if (!h1El) return;

    const page = parseInt(state.page || 1, 10) || 1;
    const parts = buildHeadingParts(state);

    let h1 = parts.length ? parts.join(" ") : "Спецтехника и оборудование  от компании GLOG";
    if (page > 1) h1 += " — страница " + page;

    h1El.textContent = h1;
  }

  function setTitleFromState(state) {
    const page = parseInt(state.page || 1, 10) || 1;
    const parts = buildHeadingParts(state);

    let t = parts.length ? parts.join(" ") : "Спецтехника и оборудование  от компании GLOG";
    if (page > 1) t += " — страница " + page;

    t += " — GLOG";

    document.title = t;
  }

  function getSelectValue(sel) {
    const el = qs(sel);
    if (!el) return "";
    const v = (el.value || "").trim();
    return v === "0" ? "" : v;
  }

  function setSelectValue(sel, value) {
    const el = qs(sel);
    if (!el) return;

    if (!value) {
      el.value = "0";
      return;
    }

    // прямое совпадение
    let opt = Array.from(el.options).find((o) => (o.value || "") === value);

    // fallback: совпадение без учета регистра
    if (!opt) {
      const vLow = String(value).toLowerCase();
      opt = Array.from(el.options).find((o) => String(o.value || "").toLowerCase() === vLow);
    }

    el.value = opt ? opt.value : "0";
  }
  
    // ---------- FACETS (обновление опций других селектов) ----------

  function hasOption(selectEl, value) {
    if (!selectEl) return false;
    return Array.from(selectEl.options).some((o) => String(o.value) === String(value));
  }

  function renderSelect(selectEl, placeholderText, items, currentValue, mode) {
    // mode: "strings" | "objects"
    if (!selectEl) return;

    const prev = currentValue || "";
    const frag = document.createDocumentFragment();

    const ph = document.createElement("option");
    ph.value = "0";
    ph.textContent = placeholderText;
    frag.appendChild(ph);

    if (mode === "objects") {
      (items || []).forEach((it) => {
        const opt = document.createElement("option");
        opt.value = String(it.value || "");
        opt.textContent = String(it.label || it.value || "");
        frag.appendChild(opt);
      });
    } else {
      (items || []).forEach((v) => {
        const opt = document.createElement("option");
        opt.value = String(v);
        opt.textContent = String(v);
        frag.appendChild(opt);
      });
    }

    selectEl.innerHTML = "";
    selectEl.appendChild(frag);

    if (prev && hasOption(selectEl, prev)) {
      selectEl.value = prev;
    } else {
      selectEl.value = "0";
    }
  }

  async function refreshFacets(state, changedKey) {
    // changedKey: "type" | "brand" | "year"
    if (!window.TRUCKS_FILTERS) return;

    const typeSel = qs("#truck_type");
    const brandSel = qs("#truck_brand");
    const yearSel = qs("#truck_year");

    const payload = new URLSearchParams();
    payload.append("action", "trucks_facets");
    payload.append("nonce", TRUCKS_FILTERS.nonce);
    payload.append("type", state.type || "");
    payload.append("brand", state.brand || "");
    payload.append("year", state.year || "");

    try {
      const res = await fetch(TRUCKS_FILTERS.ajaxUrl, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
        body: payload.toString(),
      });

      const json = await res.json();
      if (!json || !json.success || !json.data) return;

      const data = json.data;

      // обновляем только "остальные" селекты, не тот который только что изменили
      if (changedKey !== "type") {
        renderSelect(typeSel, "Тип техники", data.types || [], getSelectValue("#truck_type"), "objects");
      }

      if (changedKey !== "brand") {
        renderSelect(brandSel, "Бренд", data.brands || [], getSelectValue("#truck_brand"), "strings");
      }

      if (changedKey !== "year") {
        const years = (data.years || []).map((y) => ({ value: y, label: y + " г.в." }));
        renderSelect(yearSel, "Год выпуска", years, getSelectValue("#truck_year"), "objects");
      }
    } catch (e) {
      // фасеты вторичны — молча
    }
  }

  // ---------- /FACETS ----------

//   function slugify(str) {
//     if (!str) return "";
//     return str
//       .toString()
//       .normalize("NFKD")
//       .replace(/[\u0300-\u036f]/g, "")
//       .toLowerCase()
//       .replace(/&/g, "and")
//       .replace(/[^a-z0-9]+/g, "-")
//       .replace(/^-+|-+$/g, "");
//   }

function buildPath(state) {
  const type = (state.type || "").trim();
  const brand = (state.brand || "").trim();
  const year = (state.year || "").trim();
  const page = parseInt(state.page || 1, 10) || 1;

  const hasFilters = !!(type || brand || year);

  // без фильтров — обычный архив
  if (!hasFilters) {
    if (page > 1) return "/trucks/page/" + page + "/";
    return "/trucks/";
  }

  // с фильтрами — ВСЕГДА канон /trucks/filter/{type|any-type}/{brand|any-brand}/{year|any-year}/
  const segType = type ? encodeURIComponent(type) : "any-type";
  const segBrand = brand ? encodeURIComponent(brand) : "any-brand";
  const segYear = year ? encodeURIComponent(year) : "any-year";

  let path = "/trucks/filter/" + segType + "/" + segBrand + "/" + segYear + "/";
  if (page > 1) path += "page/" + page + "/";
  return path;
}

//   function mapBrandFromSegment(seg) {
//     const map = window.TRUCKS_FILTERS && TRUCKS_FILTERS.brandMap ? TRUCKS_FILTERS.brandMap : null;
//     if (!map) return seg || "";

//     const raw = (seg || "").trim();
//     if (!raw) return "";

//     const slug = slugify(raw);
//     return map[slug] ? map[slug] : raw;
//   }

function parsePath(pathname) {
  let type = "",
      brand = "",
      year = "",
      page = 1;

  let m;

  // ТОЛЬКО каноника:
  // /trucks/filter/{any-type|type}/{any-brand|brand}/{any-year|year}/page/{n}/
  m = pathname.match(
    /^\/trucks\/filter\/([^/]+)\/([^/]+)\/([^/]+)(?:\/page\/(\d+)\/)?\/?$/
  );

  if (m) {
    const t = decodeURIComponent(m[1]);
    const b = decodeURIComponent(m[2]);
    const y = decodeURIComponent(m[3]);

    type  = (t !== "any-type")  ? t : "";
    brand = (b !== "any-brand") ? b : "";
    year  = (y !== "any-year")  ? y : "";

    page = m[4] ? parseInt(m[4], 10) || 1 : 1;

    return { type, brand, year, page };
  }

  // /trucks/page/{n}/
  m = pathname.match(/^\/trucks\/page\/(\d+)\/?$/);
  if (m) {
    page = parseInt(m[1], 10) || 1;
    return { type: "", brand: "", year: "", page };
  }

  // всё остальное — считаем архивом
  return { type: "", brand: "", year: "", page: 1 };
}

  function updateUrl(state, mode) {
    const newPath = buildPath(state);

    console.log("[trucks] updateUrl:", mode, window.location.pathname, "=>", newPath);

    try {
      if (mode === "replace") {
        window.history.replaceState(state, "", newPath);
      } else {
        window.history.pushState(state, "", newPath);
      }
    } catch (e) {
      console.log("[trucks] history API error, fallback to location.assign", e);
      window.location.assign(newPath);
    }
  }

  function extractPageFromHref(href) {
    if (!href) return 1;

    const m = href.match(/\/page\/(\d+)\//);
    if (m && m[1]) return parseInt(m[1], 10);

    const m2 = href.match(/[?&]paged=(\d+)/);
    if (m2 && m2[1]) return parseInt(m2[1], 10);

    return 1;
  }

  async function loadTrucks(page) {
    const resultsRow = qs("#trucks-results");
    const paginationWrap = qs("#trucks-pagination");

    // грузим только если есть куда рендерить и есть конфиг
    if (!resultsRow || !paginationWrap || !window.TRUCKS_FILTERS) return;

    currentPage = page;

    const payload = new URLSearchParams();
    payload.append("action", "trucks_filter");
    payload.append("nonce", TRUCKS_FILTERS.nonce);
    payload.append("type", getSelectValue("#truck_type"));
    payload.append("brand", getSelectValue("#truck_brand"));
    payload.append("year", getSelectValue("#truck_year"));
    payload.append("page", String(page));

    resultsRow.classList.add("is-loading");

    try {
      const res = await fetch(TRUCKS_FILTERS.ajaxUrl, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
        body: payload.toString(),
      });

      const json = await res.json();

      if (json && json.success && json.data) {
        resultsRow.innerHTML = json.data.cards_html || "";
        paginationWrap.innerHTML = json.data.pagination_html || "";
      } else {
        resultsRow.innerHTML =
          '<div class="col-12"><div class="trucks-empty">Ошибка загрузки.</div></div>';
        paginationWrap.innerHTML = "";
      }
    } catch (e) {
      resultsRow.innerHTML =
        '<div class="col-12"><div class="trucks-empty">Ошибка сети.</div></div>';
      paginationWrap.innerHTML = "";
    } finally {
      resultsRow.classList.remove("is-loading");
    }
  }

  function applyState(state, doLoad) {
    setSelectValue("#truck_type", state.type);
    setSelectValue("#truck_brand", state.brand);
    setSelectValue("#truck_year", state.year);

    // синхронизируем H1 + title
    setH1FromState(state);
    setTitleFromState(state);

    const p = parseInt(state.page || 1, 10) || 1;
    if (doLoad) loadTrucks(p);
  }

  function init() {
    // главное: ориентируемся на селекты, а не на контейнеры результатов
    const typeSel = qs("#truck_type");
    const brandSel = qs("#truck_brand");
    const yearSel = qs("#truck_year");

    if (!typeSel && !brandSel && !yearSel) return;

    const resultsRow = qs("#trucks-results");
    const paginationWrap = qs("#trucks-pagination");

    console.log(
      "listeners attached",
      !!typeSel,
      !!brandSel,
      !!yearSel,
      "results:",
      !!resultsRow,
      "pagination:",
      !!paginationWrap
    );

    // старт: читаем URL -> выставляем селекты -> НЕ грузим (сервер уже отрендерил)
    const initial = parsePath(window.location.pathname);
    updateUrl(initial, "replace");
    applyState(initial, false);
    refreshFacets(initial, null);

    const onChange = async function () {
      console.log("onChange fired", window.location.pathname);
    
      const state = {
        type: getSelectValue("#truck_type"),
        brand: getSelectValue("#truck_brand"),
        year: getSelectValue("#truck_year"),
        page: 1,
      };
    
      const changedId = (this && this.id) ? this.id : "";
      const changedKey =
        changedId === "truck_type" ? "type" :
        changedId === "truck_brand" ? "brand" :
        changedId === "truck_year" ? "year" :
        null;
    
      updateUrl(state, "push");
      setH1FromState(state);
      setTitleFromState(state);
    
      if (resultsRow && paginationWrap) {
        loadTrucks(1);
      }
    
      if (changedKey) {
        await refreshFacets(state, changedKey);
      }
    };

    if (typeSel) typeSel.addEventListener("change", onChange);
    if (brandSel) brandSel.addEventListener("change", onChange);
    if (yearSel) yearSel.addEventListener("change", onChange);

    // пагинация: вешаем только если контейнер существует
    if (paginationWrap && resultsRow) {
      paginationWrap.addEventListener("click", function (e) {
        const a = e.target.closest("a.page-numbers");
        if (!a) return;

        e.preventDefault();

        const page = extractPageFromHref(a.getAttribute("href"));
        if (!page || page === currentPage) return;

        const state = {
          type: getSelectValue("#truck_type"),
          brand: getSelectValue("#truck_brand"),
          year: getSelectValue("#truck_year"),
          page: page,
        };

        updateUrl(state, "push");
        setH1FromState(state);
        setTitleFromState(state);
        loadTrucks(page);

        window.scrollTo({
          top: resultsRow.getBoundingClientRect().top + window.scrollY - 120,
          behavior: "smooth",
        });
      });
    }

    // Back/Forward
    window.addEventListener("popstate", function (e) {
      const st = e.state || parsePath(window.location.pathname);
      applyState(st, true);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();