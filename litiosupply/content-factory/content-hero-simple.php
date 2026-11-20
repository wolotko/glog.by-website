<div class="section position-relative pb-0" smallhero>
    <div class="r-container">
        <div class="row">
            <div class="col">
                <?php if(is_singular('news')) : ?>
                    <a href="/news/">
                        <svg fill="#871a45" width="24px" height="24px" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                            <path d="M615.177 345 0 960.292l615.177 615.177 81.25-81.25-476.467-476.466H1920V902.83H219.96l476.467-476.58z" fill-rule="evenodd"/>
                        </svg>
                        <span>вернуться к новостям</span>
                    </a>
                <?php endif; ?>
                <h1 class="fw-semibold"><?php echo $args['title']; ?></h1>
            </div>
        </div>
    </div>
</div>