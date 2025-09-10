//  -----------------------------------------------------------------------------------

//     Template Name: ki-admin Admin
//     Template URI: http://admin.la-themes.com/ki-admin/theme
//     Description: This is Admin theme
//     Author: la-themes
//     Author URI: https://themeforest.net/user/la-themes

// -----------------------------------------------------------------------------------

// 01. Horizontal Nav js
// 02. Flag  Icon Js
// 03. copy js
// 04. sidebar toggle js
// 05. List page js
// 06. Sidebar scroll js
// 07. Loader JS
// 08. tap on top
// 09. flag dropdown
// 10. hide-show
// 11. dark mode js
// 12. close on click js
// 13. searchbar js
// 14. closeCollapse js
// 15. Modal js


// >>-- 01 Horizontal Nav Js --<<
let navBar = $(".main-nav");
let size = "150px";
let leftsideLimit = -100;
let navbarSize;
let containerWidth;
let maxNavbarLimit;

function setUpHorizontalHeader() {
  navbarSize = navBar.width();
  containerWidth = ($(".simplebar-content").width())
  maxNavbarLimit = -(navbarSize - containerWidth);
  if ($("nav").hasClass("horizontal-sidebar")) {
    $(".menu-next").removeClass("d-none");
    $(".menu-previous").removeClass("d-none");
  } else {
    navBar.css("marginLeft",0)
    $(".menu-next").addClass("d-none");
    $(".menu-previous").addClass("d-none");
  }
  $(".horizontal-sidebar .show").removeClass("show");
}

$(document).on('click', '.menu-previous', function (e) {
  let layoutOption = getLocalStorageItem("layout-option","ltr");
  let attribute = (layoutOption == 'ltr' || layoutOption == 'box-layout') ? 'marginLeft' : 'marginRight';
  let currentPosition = parseInt(navBar.css(attribute));
  if (currentPosition < 0) {
    navBar.css(`${attribute}`, "+=" + size)
    $(".menu-next").removeClass("d-none");
    $(".menu-previous").removeClass("d-none");
    if (currentPosition >= leftsideLimit) {
      $(this).addClass("d-none");
    }
  }
})

$(document).on('click', '.menu-next', function (e) {
  let layoutOption = getLocalStorageItem("layout-option","ltr");
  let attribute = (layoutOption == 'ltr' || layoutOption == 'box-layout') ? 'marginLeft' : 'marginRight';
  let currentPosition = parseInt(navBar.css(attribute));
  if (currentPosition >= maxNavbarLimit) {
    $(".menu-next").removeClass("d-none");
    $(".menu-previous").removeClass("d-none");
    navBar.css(`${attribute}`, "-=" + size)
    if (currentPosition - parseInt(size) <= maxNavbarLimit) {
      $(this).addClass("d-none");
    }
  }
})

$(function () {
    setUpHorizontalHeader();
  let themeMode = getLocalStorageItem('theme-mode', 'light')
    setTimeout(() => {
    $('body').addClass(`${themeMode}`)
  }, 1500);
});


// >>-- 02 Flag  Icon Js --<<
$(function () {
  let text = $(".selected i").attr('class')
  $(".flag i").prop('class', text);
  $(document).on('click', '.lang', function () {
    $(".lang").removeClass("selected");
    $(this).addClass("selected");
    text = $(".selected i").attr('class')
    $(".flag i").prop('class', text);
  });
})



// >>-- 03 Copy js --<<
function copyvalue() {
  let temp = document.createElement('input');
  let texttoCopy = document.getElementById('copyText2').innerHTML;
  temp.type = 'input';
  temp.setAttribute('value', texttoCopy);
  document.body.appendChild(temp);
  temp.select();
  document.execCommand("copy");
  temp.remove();
  console.timeEnd('time2');
}



// >>-- 04 Sidebar toggle js --<<
$(document).on('click', '.header-toggle', function () {
  const $nav = $("nav");
  const $overlay = $(".sidebar-overlay");
  const isMobile = window.innerWidth <= 768;

  if (isMobile) {
    // Su mobile, toggle della classe sidebar-open
    $nav.toggleClass("sidebar-open");
    $overlay.toggleClass("active");

    // Previeni lo scroll della pagina quando la sidebar è aperta
    if ($nav.hasClass("sidebar-open")) {
      $("body").css("overflow", "hidden");
      $("html").css("overflow", "hidden");

      // Su mobile, disabilita SimpleBar e crea wrapper di scroll personalizzato
      const simpleBarElement = document.getElementById('app-simple-bar');
      if (simpleBarElement && simpleBarElement.SimpleBar) {
        simpleBarElement.SimpleBar.unMount();
      }

      // Su mobile, crea un wrapper di scroll personalizzato
      const navElement = document.querySelector('nav .app-nav');
      if (navElement && !navElement.querySelector('.mobile-scroll-wrapper')) {
        const content = navElement.querySelector('.simplebar-content');
        if (content) {
          // Crea wrapper di scroll con padding bottom per la barra degli indirizzi
          const wrapper = document.createElement('div');
          wrapper.className = 'mobile-scroll-wrapper';
          wrapper.style.cssText = `
            height: calc(100vh - 200px);
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 80px;
          `;

          // Sposta il contenuto nel wrapper
          content.parentNode.insertBefore(wrapper, content);
          wrapper.appendChild(content);

          // Assicurati che tutte le voci del menu siano visibili su mobile
          const menuItems = wrapper.querySelectorAll('li');
          menuItems.forEach(item => {
            if (item.classList.contains('d-none') && item.classList.contains('d-sm-block')) {
              item.classList.remove('d-none', 'd-sm-block');
              item.classList.add('d-block');
            }
          });
        }
      }
    } else {
      $("body").css("overflow", "");
      $("html").css("overflow", "");

      // Su mobile, riabilita SimpleBar e rimuovi wrapper personalizzato
      const simpleBarElement = document.getElementById('app-simple-bar');
      if (simpleBarElement && !simpleBarElement.SimpleBar) {
        new SimpleBar(simpleBarElement, {
          autoHide: true,
          scrollbarMinSize: 40,
          forceVisible: 'y'
        });
      }

      // Su mobile, rimuovi wrapper di scroll personalizzato
      const navElement = document.querySelector('nav .app-nav');
      if (navElement) {
        const wrapper = navElement.querySelector('.mobile-scroll-wrapper');
        if (wrapper) {
          const content = wrapper.querySelector('.simplebar-content');
          if (content) {
            // Riporta il contenuto alla posizione originale
            wrapper.parentNode.insertBefore(content, wrapper);
            wrapper.remove();
          }
        }
      }
    }
  } else {
    // Su desktop, toggle della classe semi-nav
    $nav.toggleClass("semi-nav");
  }
});

// Chiudi sidebar cliccando sull'overlay
$(document).on('click', '.sidebar-overlay', function () {
  const $nav = $("nav");
  const $overlay = $(".sidebar-overlay");

  $nav.removeClass("sidebar-open");
  $overlay.removeClass("active");
  $("body").css("overflow", "");
  $("html").css("overflow", "");
});

// Previeni lo scroll della pagina quando si tocca la sidebar su mobile
$(document).on('touchstart touchmove', 'nav.sidebar-open', function(e) {
  e.stopPropagation();
});

// Previeni lo scroll della pagina quando si scrolla nella sidebar
$(document).on('scroll', 'nav.sidebar-open .app-nav', function(e) {
  e.stopPropagation();
});

$(".toggle-semi-nav").on("click", function () {
  // Usa la logica originale del template
  $("nav").removeClass("semi-nav");
});


// >>-- 05 List page js --<<
const $window = $(window);
const $nav = $('nav');
const $contactListbox = $(".contact-listbox");

// Aggiungi CSS per sovrascrivere le regole del template su tablet
const tabletSidebarCSS = `
<style>
/* Su tablet (768px-1199px), non forzare automaticamente semi-nav */
@media screen and (min-width: 768px) and (max-width: 1199px) {
    .app-wrapper nav {
        width: var(--sidebar-width) !important;
    }

    .app-wrapper nav .app-logo .logo-full {
        display: inline-block !important;
    }

    .app-wrapper nav .app-logo .logo-icon {
        display: none !important;
    }

    .app-wrapper nav .app-nav .menu-title span {
        display: inline !important;
        text-overflow: unset !important;
        overflow: unset !important;
        white-space: unset !important;
        color: rgba(var(--dark), 1) !important;
    }

    /* Solo quando ha esplicitamente la classe semi-nav, allora comprimi */
    .app-wrapper nav.semi-nav {
        width: var(--semi-nav) !important;
    }

    .app-wrapper nav.semi-nav .app-logo .logo-full {
        display: none !important;
    }

    .app-wrapper nav.semi-nav .app-logo .logo-icon {
        display: inline-block !important;
    }

    .app-wrapper nav.semi-nav .app-nav .menu-title span {
        display: none !important;
    }
}
</style>
`;

// Inserisci il CSS nel head
$('head').append(tabletSidebarCSS);

// Event listener for click
$contactListbox.on("click", function () {
    $(this).toggleClass("stared");
});

function resize() {
    // Su mobile (< 768px) la sidebar è sempre nascosta
    if ($window.width() < 768) {
        $nav.removeClass('semi-nav');
    }
    // Su tablet e desktop (>= 768px) non forzare nessuno stato
    // Lascia che l'utente controlli con il toggle
}
$(function () {
    resize();
});

window.addEventListener("resize", () => {
    resize();
});

// >>-- 06 Sidebar scroll js --<<
const myElement = document.getElementById('app-simple-bar');
if (myElement) {
    new SimpleBar(myElement, {
        autoHide: false,
        scrollbarMinSize: 40,
        forceVisible: 'y'
    });
}

// Sidebar active class js
$(function () {
    const current = location.pathname.split('/').pop();
    const $mainNavLinks = $('.main-nav li a');

    $mainNavLinks.each(function () {
        const $this = $(this);
        const linkHref = $this.attr("href").split('/').pop();

        if (current === linkHref) {
            const $parentLi = $this.parent('li');
            const $parentUl = $this.parent().parent().parent();
            const $grandParentUl = $parentUl.parent().parent().parent();

            if ($grandParentUl.hasClass("another-level")) {
                $grandParentUl.closest('li').children().addClass('show').attr("aria-expanded", "true");
            }

            $parentUl.children().addClass('show').attr("aria-expanded", "true");
            $parentLi.addClass('active');
        }
    });
});
// >>-- 07 Loader JS --<<
$('.loader-wrapper').fadeOut('slow', function () {
    $(this).remove();
});


// >>-- 08 tap on top --<<
let calcScrollValue = () => {
    const $scrollProgress = document.getElementsByClassName("go-top")[0];
    const $progressValue = document.getElementsByClassName("progress-value")[0];
    const docElement = document.documentElement;

    const pos = docElement.scrollTop;
    const calcHeight = docElement.scrollHeight - docElement.clientHeight;
    const scrollValue = Math.round((pos * 100) / calcHeight);

    if (pos > 100) {
        $scrollProgress.style.display = 'grid';
    } else {
        $scrollProgress.style.display = 'none';
    }

    $scrollProgress.addEventListener("click", () => {
        docElement.scrollTop = 0;
    });

    $scrollProgress.style.background = `conic-gradient(rgba(var(--primary), 1) ${scrollValue}%, rgba(var(--primary), 1) ${scrollValue}%)`;
};

window.onscroll = calcScrollValue;


// >>-- 09 Flag dropdown --<<
$(function () {
    const $flagImg = $(".flag img");
    const $flagIcon = $(".flag i");
    const $langs = $(".lang");

    // Initialize image and icon from the selected language
    const initialSelected = $(".lang.selected");
    const initialImgSrc = initialSelected.find("img").attr("src");
    const initialIconClass = initialSelected.find("i").attr("class");

    $flagImg.prop("src", initialImgSrc);
    $flagIcon.prop("class", initialIconClass);

    $(document).on("click", ".lang", function () {
        $langs.removeClass("selected");

        const $this = $(this);
        $this.addClass("selected");

        const newImgSrc = $this.find("img").attr("src");
        const newIconClass = $this.find("i").attr("class");

        $flagImg.prop("src", newImgSrc);
        $flagIcon.prop("class", newIconClass);
    });
});



// >>-- 10 Hide-show --<<

const appElement = document.getElementById("myapp");
const $buttonContent = $("#button-content");
const $buttonCode = $("#button-code");

function myFunction() {
    if (appElement.style.display === "none") {
        appElement.style.display = "block";
        const buttoncontent = $buttonContent.html().replace(/</g, "&lt;").replace(/>/g, "&gt;");
        $buttonCode.html(buttoncontent);
    } else {
        appElement.style.display = "none";
        $buttonCode.html("");
    }
}


// >>-- 11 Dark mode js --<<

const themeToggle = document.querySelector(".header-dark");

if (themeToggle) {
    themeToggle.addEventListener("click", () => {
        document.querySelector(".sun-logo")?.classList.toggle("sun");
        document.querySelector(".moon-logo")?.classList.toggle("moon");

        const isDark = document.body.classList.contains("dark");
        document.body.classList.toggle("dark", !isDark);
        document.body.classList.toggle("light", isDark);
        setLocalStorageItem('theme-mode', isDark ? 'light' : 'dark');
    });
}
function appendHtml() {
  let div = document.getElementsByClassName('app-wrapper');
  div.innerHTML += '<p>This is some HTML code</p>';
}
window.onload = function () {
  appendHtml();
}

// >>-- 12 Close on click js --<<

$(document).on('click', '.close-btn', function () {
  let targetItem = $(this).closest(".head-box");
  let targetParent = targetItem.parent();
  $(this).parent().parent().remove();
  if (targetParent.find(".head-box").length <= 0) {
    targetParent.parent().parent().find('.head-box-footer').addClass('d-none');
    targetParent.parent().parent().find('.offcanvas-body').addClass('h-auto');
  }
});

 // >>-- 13 Searchbar js --<<
$(document).on('keyup', '.search-filter', function () {
    const search = $(this).val().toLowerCase();
    $('.search-list-item').each(function () {
        const item = $(this);
        const contentElement = item.find('.search-list-content h6');
        const contentText = contentElement.text().toLowerCase();

        if (contentText.includes(search)) {
            item.show();
            const highlightedText = contentText.replace(new RegExp(search, 'gi'), function (match) {
                return `<span class="highlight-searchtext">${match}</span>`;
            });
            contentElement.html(highlightedText);
        } else {
            item.hide();
        }
    });
});

// >>-- 14 CloseCollapse js --<<
const closeCollaps = document.querySelectorAll('.main-nav li a[data-bs-toggle="collapse"]');

closeCollaps.forEach((element) => {
    element.addEventListener('click', () => {
        const parent = element.closest('.collapse');
        const all = document.querySelectorAll('.main-nav ul.collapse');

        all.forEach((e) => {
            if (e !== parent) {
                e.classList.remove('show');
                const ariaExpand = e.previousElementSibling;
                if (ariaExpand) ariaExpand.setAttribute('aria-expanded', 'false');
            }
        });

        parent?.classList.add('show');
        const ariaExpand = element;
        if (ariaExpand) ariaExpand.setAttribute('aria-expanded', 'true');
    });
});
// >>-- 15  Modal js --<<

$(function () {
  $('#welcomeCard').modal('show');
});

function copyTextToClipboard(text) {
    let textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
}

// >>-- Global Search Functionality --<<
let searchTimeout;
let currentSearchQuery = '';
let searchInitialized = false;

// Initialize global search when DOM is ready
$(document).ready(function() {
    // Add a small delay to ensure all elements are rendered
    setTimeout(function() {
        if (!searchInitialized) {
            initializeGlobalSearch();
        }
    }, 100);
});

function initializeGlobalSearch() {
    // Initialize desktop search
    const searchInput = document.getElementById('globalSearchInput');
    const searchForm = document.getElementById('globalSearchForm');
    const searchResultsDropdown = document.getElementById('searchResultsDropdown');

    // Initialize mobile search
    const searchInputMobile = document.getElementById('globalSearchInputMobile');
    const searchFormMobile = document.getElementById('globalSearchFormMobile');
    const searchResultsDropdownMobile = document.getElementById('searchResultsDropdownMobile');

    if (searchInitialized) {
        console.log('Search already initialized, skipping');
        return;
    }

    console.log('Initializing global search...');
    searchInitialized = true;

    // Initialize desktop search if elements exist
    if (searchInput && searchForm && searchResultsDropdown) {
        initializeSearchInstance(searchInput, searchForm, searchResultsDropdown, 'desktop');
    }

    // Initialize mobile search if elements exist
    if (searchInputMobile && searchFormMobile && searchResultsDropdownMobile) {
        initializeSearchInstance(searchInputMobile, searchFormMobile, searchResultsDropdownMobile, 'mobile');
    }
}

function initializeSearchInstance(searchInput, searchForm, searchResultsDropdown, type) {
    console.log(`Initializing ${type} search...`);

    // Handle input changes with debounce
    searchInput.addEventListener('input', function(e) {
        try {
            const query = e.target.value.trim();
            currentSearchQuery = query;

            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            if (query.length >= 2) {
                // Show loading state
                showSearchLoading(searchResultsDropdown, type);

                // Debounce search request
                searchTimeout = setTimeout(() => {
                    performSearch(query, searchResultsDropdown, type);
                }, 300);
            } else if (query.length === 0) {
                showSearchPlaceholder(searchResultsDropdown, type);
            } else {
                showSearchEmpty(searchResultsDropdown, type);
            }
        } catch (error) {
            console.error('Error in search input handler:', error);
        }
    });

    // Handle form submission
    searchForm.addEventListener('submit', function(e) {
        const query = searchInput.value.trim();
        if (query.length < 2) {
            e.preventDefault();
            return false;
        }
        // Let the form submit normally to the search page
    });

    // Handle click outside to close dropdown
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResultsDropdown.contains(e.target)) {
            hideSearchDropdown(searchResultsDropdown);
        }
    });

    // Handle escape key to close dropdown
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideSearchDropdown(searchResultsDropdown);
            searchInput.blur();
        }
    });
}

function performSearch(query, searchResultsDropdown, type) {
    if (query !== currentSearchQuery) {
        return; // Query has changed, ignore this result
    }

    // Use dynamic configuration if available, fallback to hardcoded URL
    const apiUrl = (typeof window.SearchConfig !== 'undefined') ?
        window.SearchConfig.apiUrl : '/search/api';
    const csrfToken = (typeof window.SearchConfig !== 'undefined') ?
        window.SearchConfig.csrfToken : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch(`${apiUrl}?q=${encodeURIComponent(query)}&limit=5`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && query === currentSearchQuery) {
            displaySearchResults(data.results, query, searchResultsDropdown, type);
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        if (query === currentSearchQuery) {
            showSearchError(searchResultsDropdown, type);
        }
    });
}

function displaySearchResults(results, query, searchResultsDropdown, type) {
    const searchResults = searchResultsDropdown.querySelector('.search-results');
    const searchLoading = searchResultsDropdown.querySelector('.search-loading');
    const searchEmpty = searchResultsDropdown.querySelector('.search-empty');
    const searchPlaceholder = searchResultsDropdown.querySelector('.search-placeholder');

    // Hide all states
    [searchLoading, searchEmpty, searchPlaceholder].forEach(el => {
        if (el) el.style.display = 'none';
    });

    if (!results || Object.keys(results).length === 0) {
        showSearchEmpty(searchResultsDropdown, type);
        return;
    }

    let html = '';
    let totalResults = 0;

    // Generate results HTML for each category
    Object.keys(results).forEach(category => {
        const categoryData = results[category];
        if (categoryData.count > 0) {
            totalResults += categoryData.count;
            html += generateCategoryResults(category, categoryData, query);
        }
    });

    if (totalResults === 0) {
        showSearchEmpty(searchResultsDropdown, type);
        return;
    }

    // Add "View all results" link
    const searchUrl = (typeof window.SearchConfig !== 'undefined') ?
        window.SearchConfig.searchUrl : '/search';

    html += `
        <div class="border-top p-2 text-center">
            <a href="${searchUrl}?q=${encodeURIComponent(query)}"
               class="btn btn-sm btn-outline-primary w-100">
                <i class="ph ph-magnifying-glass me-1"></i>
                Vedi tutti i risultati (${totalResults})
            </a>
        </div>
    `;

    searchResults.innerHTML = html;
    searchResults.style.display = 'block';
    showSearchDropdown(searchResultsDropdown);
}

function generateCategoryResults(category, categoryData, query) {
    const categoryNames = {
        'poems': 'Poesie',
        'events': 'Eventi',
        'videos': 'Video',
        'gigs': 'Gig',
        'users': 'Utenti'
    };

    const categoryIcons = {
        'poems': 'ph-pen-nib',
        'events': 'ph-calendar',
        'videos': 'ph-video',
        'gigs': 'ph-briefcase',
        'users': 'ph-users'
    };

    const categoryColors = {
        'poems': 'info',
        'events': 'success',
        'videos': 'warning',
        'gigs': 'primary',
        'users': 'secondary'
    };

    let html = `
        <div class="search-category mb-2">
            <div class="px-3 py-2 bg-light">
                <h6 class="mb-0 text-${categoryColors[category]}">
                    <i class="ph ${categoryIcons[category]} me-1"></i>
                    ${categoryNames[category]}
                    <span class="badge bg-${categoryColors[category]} ms-1">${categoryData.count}</span>
                </h6>
            </div>
            <div class="search-category-results">
    `;

    categoryData.data.forEach(item => {
        html += generateItemResult(category, item, query);
    });

    html += `
            </div>
        </div>
    `;

    return html;
}

function generateItemResult(category, item, query) {
    let html = '<div class="search-item p-2 border-bottom">';

    if (category === 'poems') {
        html += `
            <a href="/poems/${item.slug}" class="text-decoration-none">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-dark">${highlightText(item.title, query)}</h6>
                        <p class="mb-1 text-muted small">${highlightText(item.content ? item.content.substring(0, 80) : '', query)}...</p>
                        <small class="text-muted">
                            <i class="ph ph-user me-1"></i>${item.user.name}
                        </small>
                    </div>
                </div>
            </a>
        `;
    } else if (category === 'events') {
        html += `
            <a href="/events/${item.id}" class="text-decoration-none">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-dark">${highlightText(item.title, query)}</h6>
                        <p class="mb-1 text-muted small">${highlightText(item.description ? item.description.substring(0, 80) : '', query)}...</p>
                        <small class="text-muted">
                            <i class="ph ph-map-pin me-1"></i>${item.city}
                        </small>
                    </div>
                </div>
            </a>
        `;
    } else if (category === 'videos') {
        html += `
            <a href="/videos/${item.id}" class="text-decoration-none">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-dark">${highlightText(item.title, query)}</h6>
                        <p class="mb-1 text-muted small">${highlightText(item.description ? item.description.substring(0, 80) : '', query)}...</p>
                        <small class="text-muted">
                            <i class="ph ph-user me-1"></i>${item.user.name}
                        </small>
                    </div>
                </div>
            </a>
        `;
    } else if (category === 'gigs') {
        html += `
            <a href="/gigs/${item.id}" class="text-decoration-none">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-dark">${highlightText(item.title, query)}</h6>
                        <p class="mb-1 text-muted small">${highlightText(item.description ? item.description.substring(0, 80) : '', query)}...</p>
                        <small class="text-muted">
                            <i class="ph ph-map-pin me-1"></i>${item.location}
                        </small>
                    </div>
                </div>
            </a>
        `;
    } else if (category === 'users') {
        html += `
            <a href="/user/${item.id}" class="text-decoration-none">
                <div class="d-flex align-items-center">
                    <img src="${item.avatar || '/assets/images/default-avatar.png'}"
                         alt="${item.name}"
                         class="rounded-circle me-2"
                         style="width: 32px; height: 32px;">
                    <div>
                        <h6 class="mb-0 text-dark">${highlightText(item.name, query)}</h6>
                        <small class="text-muted">${item.email}</small>
                    </div>
                </div>
            </a>
        `;
    }

    html += '</div>';
    return html;
}

function highlightText(text, query) {
    if (!query || !text) return text;
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<mark class="bg-warning">$1</mark>');
}

function showSearchLoading(searchResultsDropdown, type) {
    const searchLoading = searchResultsDropdown.querySelector('.search-loading');
    const searchResults = searchResultsDropdown.querySelector('.search-results');
    const searchEmpty = searchResultsDropdown.querySelector('.search-empty');
    const searchPlaceholder = searchResultsDropdown.querySelector('.search-placeholder');

    [searchResults, searchEmpty, searchPlaceholder].forEach(el => {
        if (el) el.style.display = 'none';
    });

    if (searchLoading) {
        searchLoading.style.display = 'block';
    }

    showSearchDropdown(searchResultsDropdown);
}

function showSearchEmpty(searchResultsDropdown, type) {
    const searchLoading = searchResultsDropdown.querySelector('.search-loading');
    const searchResults = searchResultsDropdown.querySelector('.search-results');
    const searchEmpty = searchResultsDropdown.querySelector('.search-empty');
    const searchPlaceholder = searchResultsDropdown.querySelector('.search-placeholder');

    [searchLoading, searchResults, searchPlaceholder].forEach(el => {
        if (el) el.style.display = 'none';
    });

    if (searchEmpty) {
        searchEmpty.style.display = 'block';
    }

    showSearchDropdown(searchResultsDropdown);
}

function showSearchPlaceholder(searchResultsDropdown, type) {
    const searchLoading = searchResultsDropdown.querySelector('.search-loading');
    const searchResults = searchResultsDropdown.querySelector('.search-results');
    const searchEmpty = searchResultsDropdown.querySelector('.search-empty');
    const searchPlaceholder = searchResultsDropdown.querySelector('.search-placeholder');

    [searchLoading, searchResults, searchEmpty].forEach(el => {
        if (el) el.style.display = 'none';
    });

    if (searchPlaceholder) {
        searchPlaceholder.style.display = 'block';
    }

    showSearchDropdown(searchResultsDropdown);
}

function showSearchError(searchResultsDropdown, type) {
    const searchLoading = searchResultsDropdown.querySelector('.search-loading');
    const searchResults = searchResultsDropdown.querySelector('.search-results');
    const searchEmpty = searchResultsDropdown.querySelector('.search-empty');
    const searchPlaceholder = searchResultsDropdown.querySelector('.search-placeholder');

    [searchLoading, searchResults, searchPlaceholder].forEach(el => {
        if (el) el.style.display = 'none';
    });

    if (searchEmpty) {
        searchEmpty.innerHTML = `
            <i class="ph ph-warning display-6 text-danger mb-2"></i>
            <p class="text-muted mb-0">Errore durante la ricerca</p>
        `;
        searchEmpty.style.display = 'block';
    }

    showSearchDropdown(searchResultsDropdown);
}

function showSearchDropdown(searchResultsDropdown) {
    try {
        if (searchResultsDropdown) {
            searchResultsDropdown.classList.add('show');
            searchResultsDropdown.style.display = 'block';
            searchResultsDropdown.style.position = 'absolute';
            searchResultsDropdown.style.top = '100%';
            searchResultsDropdown.style.left = '0';
            searchResultsDropdown.style.right = '0';
            searchResultsDropdown.style.zIndex = '1050';
        }
    } catch (error) {
        console.error('Error showing search dropdown:', error);
    }
}

function hideSearchDropdown(searchResultsDropdown) {
    try {
        if (searchResultsDropdown) {
            searchResultsDropdown.classList.remove('show');
            searchResultsDropdown.style.display = 'none';
        }
    } catch (error) {
        console.error('Error hiding search dropdown:', error);
    }
}
