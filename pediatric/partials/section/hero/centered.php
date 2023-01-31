<?php               
    global $post;
    $post_slug = $post->post_name; 
    $brand = is_brand();
    $location = is_location();
    $data= !empty($location) ? $location->phone : get_locations_for_brand($brand->ID);  
?>
<section class="hero centered<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <? if(!empty($image)) :?>
            <div class="image-continer">
                <img src="<?= !empty($image['src']) ? $image['src'] : '';?>" alt="<?= !empty($image['alt']) ? $image['alt'] : '';?>">
            </div>
            <? endif; ?>

            <div class="content-container">
                <? if(!empty($h1)) :?>
                    <h1 class="<?= !empty($h1_classes) ? implode(' ', $h1_classes) : ''; ?>"><?= !empty($h1) ? $h1 : '';?></h1>
                <? endif; ?>

                <? if(!empty($copy)) :?>
                    <p><?= $copy ;?></p>
                <? endif; ?>  

                <? if ($brand->ID == 18088 && $post_slug == 'patient-forms' ): // Smiles in Motion ?>                                      
                    <? if (!ppc_slugs($relative_url)) : ?>                        
                        <div class="inner-content">                                  
                            <? if (empty($location)) :?>                                
                                <div class="form-wrapper">                                
                                    <div class="fancy-select">
                                        <div class="fancy-select-title white">                                      
                                            Your preferred office*
                                        </div>
                                        <div class="options hidden">
                                            <div class="items">
                                                <ul class="select-options">                                                
                                                <? foreach($data as $p) : ?>  
                                                    <li>                                                 
                                                        <a href="<?= $p->new_patient_consultation_form; ?>"><?= $p->post_title; ?></a>
                                                    </li>
                                                <? endforeach; ?>          
                                                </ul>                                 
                                            </div>    
                                        </div>                                        
                                    </div>                                                                           
                                </div>  
                            <? else: ?>
                                <? if(!empty($cta)) :?>
                                <div class="cta-container"><?= $cta ;?></div>
                                <? endif; ?>            
                            <? endif ;?>
                        </div>                            
                    <? endif; ?>
                <? else: ?>
                    <? if(!empty($cta)) :?>
                    <div class="cta-container">
                        <?= $cta ;?>
                    </div>
                    <? endif; ?>
                <? endif; ?>
            </div>
        </div>
    </div>
</section>