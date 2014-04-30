<button><?php echo anchor("news/create","Create Article");?></button>
<?php foreach ($news as $news_item): ?>

    <h2><?php echo $news_item['title'] ?></h2>
    <div id="main">
        <?php echo $news_item['slug'] ?>
    </div>
    <p><?php echo anchor("news/".$news_item['slug'],"View Article");?></p>

<?php endforeach ?>