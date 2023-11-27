<?php 
$args = array(
    'post_type' => 'books',
    'post_per_page' => -1,
    'meta_key' => 'author',
    'meta_key' => 'year',
    'meta_key' => 'genre',
);
$loop = new WP_Query($args);

$img_class = 'mx-auto rounded-sm aspect-square max-h-[300px] object-contain transition-all hover:scale-110';
get_header();
?>

<main>
    <?php include('partials/hero.php'); ?>
    <section class="books__query">
        <div class="container">
            <div class="w-full mb-10 lg:mb-20 text-3xl text-center font-bold">
                <?= the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
            </div>
        <?php if ($loop->have_posts()): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-10">
            <?php while ($loop->have_posts()): 
            $loop->the_post();
            $author = get_field('author'); 
            $year = get_field('year');
            $genre = get_field('genre');
            ?>
            <div class="col-span-1">
                <article class="flex flex-col space-y-2.5 " >
                   
                    <a class="block overflow-hidden" href="<?= get_permalink(); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?= the_post_thumbnail('full', ['class' => $img_class, 'alt' => 'placeholder']);  ?>
                        <?php else: ?>
                            <img class="<?= $img_class; ?>" src="<?= plugin_dir_url( __DIR__ ).'images/book-placeholder.jpg' ?>" alt="placeholder">
                        <?php endif; ?>
                    </a>
                    <div class="text-xs">
                        <?= "Autor: <b>$author</b>, Rok wydania: <b>$year</b>, Gatunek: <b>$genre</b>"; ?>
                    </div>
                    <div class="text-xl font-bold">
                        <?= the_title(); ?>
                    </div>
                    <div class="text-xs">
                        <?= the_excerpt(); ?>
                    </div>
                    <a href="<?= get_permalink(); ?>" class="btn-primary">
                        <?= __('Czytaj dalej'); ?>
                    </a>
                    
                </article>
            </div>
            <?php endwhile; 
            wp_reset_postdata();
            ?>

        </div>
        <?php else: ?>
        <div class="w-full text-xl text-center">
            <?= __('Nie znaleziono książek'); ?>
        </div>
        <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>