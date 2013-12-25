<?php
/**
 * Created by PhpStorm.
 * User: Happensit
 * Date: 24.12.13
 * Time: 18:11
 */
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
<?php if (!$page): ?>
    <h2 class="article_title"><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
<?php endif; ?>
 <div class="article_created">
   <?php echo format_date($node->created, 'custom', 'j F Y').'г.'; ?>

 </div>

<div class="article"<?php print $content_attributes; ?>>
    <?php
    // We hide the comments and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    print render($content);
    ?>
</div>
</div>
