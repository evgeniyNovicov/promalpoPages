window.addEventListener("DOMContentLoaded",(
    swiper(".hero-slider_wrapper"),
    openSelect('.select-custm', '.select__dropdown-list_item', '.select-custm'),
    openSocialBlock('.social-box'),
    openMenu('.burger', '.menu_wrapper'),
    openPopap ('.services-info__button', '.popap_wrapper'),
    setInputMask()
));
function openPopap (selectorBtn, selectorPopup) {
    let btns =document.querySelectorAll(selectorBtn)
    let popups = document.querySelectorAll(selectorPopup)
    popups.forEach(popup => {
        let btnClose = popup.querySelector('.btn-close_wrapper')
        let overlayPopup = popup.querySelector('.popap-overlay')
        btnClose.addEventListener('click', handlerClosePopup)
        overlayPopup.addEventListener('click', function (e) {
            if (e.target.classList.contains('popap-overlay')) {
                let parrentPopup = e.target.closest('.popap_wrapper')
                parrentPopup.classList.remove('active')
            }
        })
    })
    function handlerClosePopup (e) {
        let parrentPopup = e.target.closest('.popap_wrapper')
        parrentPopup.classList.remove('active')
    }
    btns.forEach(btn => {
        btn.addEventListener('click', handlerOpenPopap)
    })
    function handlerOpenPopap(e) {
        let target = e.target
        let popap = document.querySelector(`#${target.dataset.id}`)
        popap.classList.add('active')
        if (e.target.dataset.value) {
            let inputHidden = popap.querySelector('#popap-services-input')
            inputHidden.value = e.target.dataset.value
            console.log(inputHidden.value)
        }
    }
}

function swiper (selectorSwiper) {
    const swiper = new Swiper(selectorSwiper, {
        speed: 1500,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 5000,
        },
        pagination: {
            el: '.swiper-pagination',
        },
        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
      });
}

function openMenu (selectorBtn, selectorMenu) {
    let btn =document.querySelector(selectorBtn)
    let menu =document.querySelector(selectorMenu)
    btn.addEventListener('click', handlerOpenMenu)
    function handlerOpenMenu () {
        menu.classList.toggle('active')
        btn.classList.toggle('active')
    }
}
function openSocialBlock(selectorTrigger) {
    let btn = document.querySelector(selectorTrigger)
    btn.addEventListener('click', handlerOpenSocialBlock)

    function handlerOpenSocialBlock () {
        console.log('show')
        btn.classList.add('show')
    }
    document.querySelector('body').addEventListener("click", function(e) {
        if(e.target.closest('.menu-contact-box--social')) {
        } else {
            btn .classList.remove('show')
        }
    })
}

function openSelect(selectTregger, selectLabelTrigger, selectText = '') {
    const triggerBtn = document.querySelectorAll(selectTregger);
    const selectLabels = document.querySelectorAll(selectLabelTrigger);
    let selectClick = null
    document.addEventListener('click', (e) => {
        if (e.target.closest(selectTregger)) {
        } else {
            triggerBtn.forEach(item => {
                item.parentNode.classList.remove('active');
            })
        }
    })
    triggerBtn.forEach((item) => {
        item.addEventListener('click', (e) => {
            if (item.parentNode.classList.contains('active')) {
                item.parentNode.classList.remove('active');
            } else {
                removeClassClick()
                item.parentNode.classList.add('active');
                selectClick = item.parentNode
            }
            if (item.classList.contains('trade-in__select')) {
                item.classList.add('active')
            }
        });
    });
    function removeClassClick() {
        triggerBtn.forEach((btn) => {
            btn.parentNode.classList.remove('active');
        });
    }
    function clickTextLabel() {
        selectLabels.forEach((label) => {
            label.addEventListener('click', (e) => {
                let selectHeaderText = selectClick.querySelector(selectText)
                selectHeaderText.textContent = e.target.textContent
                selectHeaderText.classList.add('active')
                removeClassClick();
            });
        });
    }
    clickTextLabel();
}
function setInputMask() {
    const telInputs = document.querySelectorAll('[type="tel"]');
    // eslint-disable-next-line no-unused-vars
    let phoneMask;
    telInputs.forEach((input) => {
        // eslint-disable-next-line no-undef
        phoneMask = IMask(input, { mask: '+{375}(00)000-00-00' });
    });
}
