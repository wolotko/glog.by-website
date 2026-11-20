<?php 
    global $post;
    $pid = $post->ID;
?>
    </main>
    <footer>
        <div class="section pb-0 bg-accent" style="padding-top:30px !important;">
            <div class="r-container">
                <div class="d-flex flex-xl-row flex-column gap-3 justify-content-between py-3">
                    <span class="text-center fs-6">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
                    </span>
                    <span class="text-center fs-6">
                        <a href="tel:+375333592626" class="fs-6"><i class="fa-solid fa-phone"></i><em>+375(33)359-26-26</em></a>
                    </span>
                    <span class="text-center fs-6">
                        <a href="mailto:info.glog.by@gmail.com" class="fs-6"><i class="fa-solid fa-envelope"></i><em>info.glog.by@gmail.com</em></a>
                    </span>
                    <span class="text-center text-color fs-6">
                        <?php if($pid == 69): ?>
                            LitioTrans LLC, Tax ID: 291859052<br>Legal address: 68 Sovetskaya St., office 44, Brest, 224030, Belarus
                        <?php else : ?>
                            ООО «ЛитиоТранс», УНП 291859052<br>Юр. адрес: 224030, г. Брест, ул. Советская, д. 68, пом.44
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php bloginfo('template_directory') ?>/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="<?php bloginfo('template_directory') ?>/js/vendor/swiper-bundle.min.js"></script>
    <script src="<?php bloginfo('template_directory') ?>/js/owl.carousel.min.js"></script>
    <script src="<?php bloginfo('template_directory') ?>/js/script.js"></script>
    <script src="<?php bloginfo('template_directory') ?>/js/swiper-script.js"></script>
    <script src="<?php bloginfo('template_directory') ?>/js/submit-form.js"></script>
    <script src="<?php bloginfo('template_directory') ?>/js/vendor/isotope.pkgd.min.js"></script>
    <script src="<?php bloginfo('template_directory') ?>/js/video_embedded.js"></script>
    <script src="<?php bloginfo('template_directory') ?>/js/vendor/fslightbox.js"></script>
    <?php wp_footer(); ?>
    
    <div id="custom-cookie-banner" class="cookie-banner">
      <div class="cookie-content">
        <h3 id="cookie-title"></h3>
        <p id="cookie-message"></p>
        <div class="cookie-buttons">
            <button class="btn-accent" id="cookie-accept"></button>
            <a id="cookie-link" href="#" target="_blank" class="cookie-link">Privacy Policy</a>
            <button class="btn-outline" id="cookie-reject"></button>
        </div>
      </div>
    </div>
    
    <style>
      .cookie-banner {
          display: none;
          position: fixed;
          bottom: 2rem;
          right: 2rem;
          max-width: 360px;
          background: white;
          color: #000;
          box-shadow: 0 4px 20px rgba(0,0,0,0.15);
          border-radius: 10px;
          padding: 1.5rem;
          z-index: 9999;
          font-family: var(--font-1);
        }
        @media only screen and (max-width: 600px)
        {
            .cookie-banner
            {
                bottom:1rem;
                right:1rem;
                left:1rem;
            }
        }
      .cookie-banner h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
      }
      .cookie-banner p {
        font-size: 1rem;
        margin-bottom: 1.25rem;
        line-height: 1.4;
      }
      .cookie-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: center;
      }
      .cookie-buttons button,
      .cookie-link {
        padding: 0.5rem 1.25rem;
        border-radius: 5px;
        font-size: 0.85rem;
        cursor: pointer;
        border: 2px solid var(--accent-color-new);
        background: transparent;
        color: var(--accent-color-new);
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
      }
      .cookie-buttons .cookie-link:hover,
      .btn-outline:hover {
        background: var(--accent-color-new);
        color: #fff;
      }
      .cookie-buttons button.btn-accent {
          background: var(--accent-color-new);
          color: #fff;
          border: 2px solid var(--accent-color-new);
          transition: background 0.2s, color 0.2s;
        }
        
        .cookie-buttons button.btn-accent:hover {
          background: #fff;
          color: var(--accent-color-new);
        }
    </style>
    
    <script>
      (function () {
        const banner = document.getElementById('custom-cookie-banner');
        const accepted = localStorage.getItem('cookie_accepted');
    
        if (!accepted) banner.style.display = 'block';
    
        const pid = document.body.getAttribute('data-pid');
        const isEnglish = pid === '69';
    
        const text = {
          en: {
            title: "We value your privacy",
            message: "We use cookies to enhance your browsing experience, serve personalised ads or content, and analyse our traffic. By clicking \"Accept All\", you consent to our use of cookies.",
            accept: "Accept All",
            reject: "Reject All",
            linkText: "Privacy Policy",
            linkHref: "https://glog.by/privacy-policy-en.pdf"
          },
          ru: {
            title: "Мы уважаем вашу конфиденциальность",
            message: "Мы используем cookies для улучшения работы сайта, персонализации контента и анализа трафика. Нажимая «Принять все», вы соглашаетесь с <a href='https://litiosupply.by/privacy-policy.pdf' target='_blank'>использованием cookies</a>.",
            accept: "Принять все",
            reject: "Отклонить все",
            linkText: "Политика конфиденциальности",
            linkHref: "https://glog.by//privacy-policy.pdf"
          }
        };
    
        const lang = isEnglish ? 'en' : 'ru';
    
        document.getElementById('cookie-title').textContent = text[lang].title;
        document.getElementById('cookie-message').innerHTML = text[lang].message;
        document.getElementById('cookie-accept').textContent = text[lang].accept;
        document.getElementById('cookie-reject').textContent = text[lang].reject;
        const link = document.getElementById('cookie-link');
        link.textContent = text[lang].linkText;
        link.href = text[lang].linkHref;
    
        document.getElementById('cookie-accept').onclick = function () {
          localStorage.setItem('cookie_accepted', 'true');
          banner.style.display = 'none';
        };
    
        document.getElementById('cookie-reject').onclick = function () {
          localStorage.setItem('cookie_accepted', 'rejected');
          banner.style.display = 'none';
        };
      })();
    </script>
</body>

</html>