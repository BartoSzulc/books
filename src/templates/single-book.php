<?php 
get_header();
$img_class = 'aspect-1/1 max-h-[600px] mx-auto';
$author = get_field('author'); 
$year = get_field('year');
$genre = get_field('genre');
?>

<main>
    <?php include('partials/hero.php'); ?>
    <section class="books__post">
        <div class="container">
            <article class="flex flex-col space-y-2.5 " >
                <div class="text-base mb-10 text-center">
                    <?= "Autor: <b>$author</b>, Rok wydania: <b>$year</b>, Gatunek: <b>$genre</b>"; ?>
                </div>
                <?php if ( has_post_thumbnail() ) : ?>
                    <?= the_post_thumbnail('full', ['class' => $img_class, 'alt' => 'placeholder']);  ?>
                <?php else: ?>
                    <img class="<?= $img_class; ?>" src="<?= plugin_dir_url( __DIR__ ).'images/book-placeholder.jpg' ?>" alt="placeholder">
                <?php endif; ?>
               
                <div class="text-3xl font-bold !my-10 text-center">
                    <?= the_title(); ?>
                </div>
                <div class="text-base">
                    <?= the_content(); ?>
                </div>      
            </article>
            <div class="w-full text-center flex items-center justify-center my-10">
                <a href="<?=  get_post_type_archive_link( 'books' ); ?>" class="btn-primary">
                    <?= __('Powrót'); ?>
                </a> 
            </div>
        </div>
    </section>
</main>


<?php 
get_footer(); 
?>