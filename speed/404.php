<?php require_once 'include/header.php';?>

<style>
        .fourzerofour_wrpper {
            width: 100%;
            height: calc(100vh - 90px);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background-image: url(<?=$bunny_image?>bg-404.webp);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            background-color: black;
            background-blend-mode: hard-light;
        }
        .four_z_four{
            text-align: center;

        }

        .four_z_four h1 {
            font-size: 200px;
            color: #aaaaaa;
        }

        .four_z_four .cta__wrapper {
            margin-top: 25px;
        }

        .four_z_four  .cta__wrapper a{
        margin: auto;
        }
        
        @media screen and (max-width: 768px)
        {
            .four_z_four h1 {
                font-size: 120px;
            }
        }
    </style>

<div class="fourzerofour_wrpper">
    <div class="four_z_four">
        <h1>404</h1>
        <p>Page not found</p>
        <div class="cta__wrapper">
            <a href="<?=HOST_URL?>" class="btn">Go back</a>
        </div>
    </div>

</div>

<?php require_once 'include/footer.php';?>