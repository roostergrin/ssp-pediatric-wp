<?
# Template Name: Virtual Tour

$brand = is_brand();
$location = is_location();

get_header();
?>

<style>
    h1 {
        font-size: 40px;
        margin: 40px auto 0;
        width: 100%;
        max-width: 800px;
        text-align: center;
        line-height: 1.2em;
    }
    @media screen and (max-width:670px) {
        h1 { 
            font-size: 32px; 
            padding-left: 30px;
            padding-right: 30px;
        }
    }
</style>

<h1>Tour our <?= $brand->post_title; ?> pediatric dentist office in <?= $location->post_title; ?></h1>

<?
partial('section.virtual-tour', [
    'tour_source' => get_field( 'virtual_tour_embed_source', get_the_ID() )
]);

get_footer();