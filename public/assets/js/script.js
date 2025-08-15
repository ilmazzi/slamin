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
  const $nav = $("nav");
  const windowWidth = $(window).width();

  // Su tablet (768px - 1199px) alterna tra semi-nav e espansa
  if (windowWidth >= 768 && windowWidth < 1199) {
    if ($nav.hasClass("semi-nav")) {
      // Espandi la sidebar
      $nav.removeClass("semi-nav");
      $nav.addClass("sidebar-expanded");
      localStorage.setItem('sidebar-tablet-state', 'expanded');
      console.log("Sidebar espansa su tablet");
    } else {
      // Comprimi la sidebar
      $nav.removeClass("sidebar-expanded");
      $nav.addClass("semi-nav");
      localStorage.setItem('sidebar-tablet-state', 'semi-nav');
      console.log("Sidebar semi-nav su tablet");
    }
  }
  // Su desktop (>= 1200px) sempre espansa
  else if (windowWidth >= 1200) {
    $nav.removeClass("semi-nav");
    $nav.addClass("sidebar-expanded");
  }
});


// >>-- 05 List page js --<<
const $window = $(window);
const $nav = $('nav');
const $contactListbox = $(".contact-listbox");

// Aggiungi CSS per gestire gli stati della sidebar
const sidebarCSS = `
<style>
/* Sidebar espansa su tablet - CSS più specifico */
.app-wrapper nav.sidebar-expanded {
    width: 280px !important;
    transform: none !important;
}

.app-wrapper nav.sidebar-expanded .app-logo .logo-full {
    display: inline-block !important;
    opacity: 1 !important;
}

.app-wrapper nav.sidebar-expanded .app-logo .logo-icon {
    display: none !important;
}

.app-wrapper nav.sidebar-expanded .app-nav .menu-title span {
    display: inline !important;
    text-overflow: unset !important;
    overflow: unset !important;
    white-space: unset !important;
    color: rgba(var(--dark), 1) !important;
    opacity: 1 !important;
}

.app-wrapper nav.sidebar-expanded .app-nav .menu-title {
    width: auto !important;
    text-align: left !important;
}

/* Sidebar nascosta su mobile */
.app-wrapper nav.sidebar-hidden {
    width: 0 !important;
    overflow: hidden !important;
    transform: translateX(-100%) !important;
}

/* Aggiusta il contenuto quando la sidebar è espansa su tablet */
@media screen and (min-width: 768px) and (max-width: 1199px) {
    .app-wrapper nav.sidebar-expanded ~ .app-content {
        margin-left: 280px !important;
        width: calc(100% - 280px) !important;
    }

    .app-wrapper nav.sidebar-expanded ~ .sidebar-overlay {
        display: none !important;
    }

    /* Forza la rimozione della classe semi-nav quando espansa */
    .app-wrapper nav.sidebar-expanded.semi-nav {
        width: 280px !important;
    }

    .app-wrapper nav.sidebar-expanded.semi-nav .app-logo .logo-full {
        display: inline-block !important;
    }

    .app-wrapper nav.sidebar-expanded.semi-nav .app-nav .menu-title span {
        display: inline !important;
    }
}
</style>
`;

// Inserisci il CSS nel head
$('head').append(sidebarCSS);

// Event listener for click
$contactListbox.on("click", function () {
    $(this).toggleClass("stared");
});

function resize() {
    const windowWidth = $window.width();

    // Su mobile (< 768px) la sidebar è sempre nascosta
    if (windowWidth < 768) {
        $nav.removeClass('semi-nav sidebar-expanded');
        $nav.addClass('sidebar-hidden');
    }
    // Su tablet (768px - 1199px) mantieni lo stato corrente o usa semi-nav come default
    else if (windowWidth < 1199) {
        $nav.removeClass('sidebar-hidden');

        // Carica lo stato salvato dall'utente
        const savedState = localStorage.getItem('sidebar-tablet-state');

        if (savedState === 'expanded') {
            $nav.removeClass('semi-nav');
            $nav.addClass('sidebar-expanded');
        } else if (savedState === 'semi-nav') {
            $nav.removeClass('sidebar-expanded');
            $nav.addClass('semi-nav');
        } else {
            // Se non ha già uno stato impostato dall'utente, usa semi-nav come default
            if (!$nav.hasClass('semi-nav') && !$nav.hasClass('sidebar-expanded')) {
                $nav.addClass('semi-nav');
            }
        }
    }
    // Su desktop (>= 1200px) la sidebar è sempre espansa
    else {
        $nav.removeClass('semi-nav sidebar-hidden');
        $nav.addClass('sidebar-expanded');
    }

    console.log('Resize - Window width:', windowWidth, 'Sidebar classes:', $nav.attr('class'));
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
