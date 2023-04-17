<link rel="stylesheet" href="//demos.codexworld.com/autocomplete-multiselect-textbox-multiple-selection-jquery-php/css/token-input.css">


     <div class="see-full add-new-product">
       <?php if(isset($message)){ ?><div class="see-red see-padding-12 see-margin-bottom see-round"> <?php echo  ' &nbsp &nbsp'.$message; ?> </div> <?php } ?>
        <?php if(isset($smessage)){ ?><div class="see-green see-padding-12 see-margin-bottom see-round"> <?php echo  ' &nbsp &nbsp'.$smessage; ?> </div> <?php } ?>
        <form method="post" enctype="multipart/form-data" id="PrdForm">
            <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                <div class="see-full shadowbox see-padding-16 see-padding-left-right-24 see-round see-margin-bottom see-margin-top">
                    <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-padding-16 see-padding-top-0 see-border-bottom">
                        <h3 class="f-26">Product Info</h3>
                    </div> 
                     <td><a href="https://myphotoprint.in/product/<?=$product_URL;?>" class="see-button see-blue" target="_blank">Preview</a></td>
                    <ul class="see-full see-padding-24 see-padding-bottom-0">
                        <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                            <li class="field">
                                <input id="cat_data" type="text" name="cat_data" class="see-full" placeholder="e.g., nice seller, 2019 fashion, specially designed" />
                            </li>
                        </div>
                        <div class="see-9 see-ltb-9 see-tb-9 see-sm-12 see-xsm-12">
                            <li class="field">
                                <span>Product Name <em>*</em></span>
                                <input type="text" name="pro_name" placeholder="Untitle" value="<?php if(!empty($getProductDetail)){ echo $product_Name; } ?>" required="" class="page-name" autocomplete="off">
                                <input class="page-url" type="text" name="pro_url" value="<?php if(!empty($getProductDetail)){ echo $product_URL; } ?>" placeholder="Product Url">
                            </li>
                        </div>
                        <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12">
                            <li class="field">
                                <span>Ribbon</span> 
                                <input type="text" name="pro_label" value="<?php if(!empty($getProductDetail)){ echo $product_label; } ?>" placeholder="e.g., New  Arriwal">
                            </li>
                        </div>
                        
                        <li class="table-heading see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                            <label>Product Video </label>
                            <div class="field">
                                <input class="count-letter"type="text" name="product_video" placeholder="Enter Video Url" value="<?php if(!empty($getProductDetail)){ echo $product_video; } ?>" >
                            </div>
                        </li>

                        <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-padding-0">
                            <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12">
                                <li class="field">
                                    <span>Price</span>
                                    <input type="tel" name="pro_price" value="<?php if(!empty($getProductDetail)){ echo $product_Price; } ?>" class="product-price" required>
                                </li>
                            </div>

                            <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12">
                                <li class="field active-deactive see-lh-28 bold-font">
                                    <div class="active-deactive-btn ">
                                        <input type="checkbox" name="disc_on" value="1" class="discount-check" <?php if(!empty($product_Disc_Type)){ echo 'checked'; } ?>>
                                        <span class="toggle-label" data-on="Yes" data-off="No"></span>
                                        <span class="toggle-handle"></span>
                                        <label>On Sale</label>
                                    </div>
                                </li>
                            </div>
                            <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12 discount-item">
                                <li class="field discount">
                                    <span>Discount</span>
                                    <input type="tel" name="disc_val" value="<?php if(!empty($product_Disc)){ echo $product_Disc; }else{ echo '0'; } ?>">
                                    <div class="percent-rupees">
                                        <span class="percentage <?php if(!empty($product_Disc_Type) && $product_Disc_Type != 'rupees'){ echo 'active-discount';} ?>"><i class="fa fa-percent"></i></span>
                                        <span class="rupees <?php if(!empty($product_Disc_Type) && $product_Disc_Type == 'rupees'){ echo 'active-discount';} ?>"><i class="fas fa-rupee-sign"></i></span>
                                    </div>
                                </li>
                                <input type="hidden" name="disc_type" id="disctype" value="<?php if(!empty($product_Disc_Type)){ echo $product_Disc_Type; }else{ echo 'percentage'; } ?>">
                            </div>
                            <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12 discount-item">
                                <li class="field">
                                    <span>Sale Price</span>
                                    <input type="tel" name="" value="<?php if(!empty($sale_price)){ echo $sale_price; }else{ echo '0'; } ?>" class="sale-price" readonly>
                                </li>
                            </div>
                        </div> 
                        <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                            <li class="field">
                                <span>Description</span>
                                <textarea rows="5" name="pro_desc" class="see_editor"><?php if(!empty($getProductDetail)){ echo $product_Desc; } ?></textarea>
                                <script type="text/javascript">
                                CKEDITOR.replace('pro_desc',{ filebrowserUploadUrl: "editor/upload.php", });
    </script>
                            </li>
                        </div>

                        <!-- Product Attribute Section -->
                        <div class="see-full see-padding-24 see-margin-top see-border-top attribSect">
                            <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                                <h3 class="f-18">Product Attributes</h3>
                            </div>
                            <div class="see-full">
                                <span class="add-attr-section-btn add-btn ac_butupdate"><i class="fa fa-plus"></i> Add a Product Attributes</span>
                            </div>
                            <div class="add-attr-section"></div>
                    <?php
                            $j = -1;
                    if(!empty($product_Attributes)){ ?>
                        <ul id="sortable" class="see-12 accordion">

                        <?php
                        foreach($product_Attributes as $key => $vals){ ?>

                            <li class="see-full see-margin-bottom">

                               <div class="attribData see-full see-border-1">
                               <?php
                                $j++;
                                foreach($vals as $valkey => $valvals){

                                    if($valkey == 'title'){ ?>
                                    <div class="attr-name">
                                        <div class="see-full see-cursor see-padding-4 see-padding-left-right-12 orange-bg theme-white-text see-border-1 see-border-top-0 see-text-left see-f-normal">
                                          <span class="see-lh-24 see-pd-inblock "><i class="fa fa-arrows-alt see-lh-24 see-padding-left-right-8 see-padding-left-0"></i><?php echo $valvals; ?> </span>
                                          <span class="deleteattrbute see-right see-dp-inblock">
                                              <input type="hidden" value="<?php echo $key; ?>" class="proattributedelete col-px-40 row-px-40">
                                              <span class="see-dp-inblock see-padding-left-right-8 see-padding-right-0" title="Delete Attribute">
                                                <i class="fa fa-trash see-text-right see-white-text see-lh-24"></i>
                                              </span>
                                          </span>
                                          <span class="see-lh-24 see-pd-inblock see-right" title="Move Attribute">
                                                <span class="see-dp-inblock see-padding-left-right-8">
                                                    <i class="fa arrow fa-angle-down see-lh-24"></i>
                                                  </span>
                                            </span>
                                        </div>
                                      </div>
                                  <?php
                                    } ?>

                            <?php if($valkey == 'title'){ ?>
                                   <div class="attr-content">
                                   <div class="see-full">
                                     <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12" mdata="56">
                                        <span class="field"><span>Attribute Title</span><input type="text" name="old_pro_attr_title[]" placeholder="Enter Attribute Title" value="<?php echo $valvals; ?>"></span>
                                    </div> <?php

                                    }


                                        if($valkey == 'required'){ ?>
                                            <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12">
                                                <span class="field">
                                                    <span> Attribute Required</span>
                                                    <select name="old_attr_required[]" class="attr_type">
                                                        <option value = "<?php echo $valvals ; ?>"><?php echo $valvals ; ?></option>
                                                        <option value = "yes">yes</option>
                                                        <option value = "no">no</option>
                                                    </select>
                                                </span>
                                            </div>  <?php
                                        }

                                        if($valkey == 'type'){ ?>
                                            <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12">
                                                <span class="field">
                                                    <span> Attribute Type</span>
                                                    <select name="old_attr_type[]" class="attr_type">
                                                        <option value = "<?php echo $valvals ; ?>"><?php echo $valvals ; ?></option>
                                                        <?php
                                                        foreach($attributeTypes as $attributeType){  ?>

                                                            <option value="<?php echo $attributeType; ?>"><?php echo $attributeType; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </span>
                                            </div>
                                        </div>
                                            <?php
                                            }  ?>



                            <?php if(is_array($valvals)){
                                      ?>
                                   <div class="see-full">

                                    <div class="sub-attribute">
                                      <div class="see-full">
                                        <div class="see-6 see-ltb-6 see-tb-6 see-sm-12 see-xsm-12">
                                          <span class="f-bold see-dp-inblock see-padding-12 see-padding-bottom-0">Title</span>
                                        </div>
                                        <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12">
                                          <span class="f-bold see-dp-inblock see-padding-12 see-padding-bottom-0">Price</span>
                                        </div>
                                      </div>
                                       <ul class="sortableAttr">
                                      <?php
                                        foreach($valvals as $valvals_key => $valvals_value){
                                        if(!empty($valvals_key)){?>

                                             <li class="sub_attr" style="" data-id="<?=$valvals_key?>">
                                                 <div class="dynamic_fields ">
                                                      <div class="genreted_sub_attr">
                                                       <div class="add_field see-full">

                                                           <div class="see-5 see-ltb-5 see-tb-5 see-sm-12 see-xsm-12">
                                                               <span class="field">
                                                               <i class="fa fa-arrows-alt see-lh-28 see-padding-left-right-8 see-padding-left-0 see-left"></i><input type="text" placeholder="Enter Dropdown name" class="subattrz_type col-per-90" name="old_subtype<?=$j;?>[]" value="<?php echo $valvals_key; ?>"></span>
                                                           </div>
                                                           <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12">
                                                                <span class="field"><input type="text" placeholder="Enter Price" class="subattrz_price" name="old_subprice<?=$j;?>[]" value="<?php echo $valvals_value; ?>"></span>
                                                           </div>
                                                           <div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12"><span class="field"><a href="javascript:void(0);" class="delete_uls"><i class="fa fa-trash see-text-right see-red-text see-lh-24"></i></a></span></div>
                                                        </div>
                                                      </div>
                                                  </div>
                                              </li>


                                    <?php  }

                                        } ?></ul></div>
                                        <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                                            <span class="see-2 see-cursor old_add_more see-button see-blue see-margin-bottom" name="<?php echo $j;?>">Add Row +</span>
                                       </div>
                                        </div>
                                    </div>
                                    <?php
                                    } ?>

                                    <?php
                                } ?>
                                </div>

                            </li>
                        <?php
                        } ?>
                        </ul>
                    <?php } ?>
                        </div>



                        <!-- Product Attribute Section END -->

                        <div class="see-full see-padding-24 see-margin-top see-border-top">
                            <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                                <h3 class="f-18">Product Info</h3>
                                <p class="f-14">Additional Info Sections Share information like "Return Policy" and "Care Instructions" with your customers.</p>
                            </div>

                            <?php if(!empty($getProductDetail)){ ?>
                            <div class="see-full">
                                <!--<ul class="product-edit-option">-->
                            <?php
                            if(!empty($product_Short_Desc)){
                                $i = count($product_Short_Desc);
                                foreach($product_Short_Desc as $product_Short_Desc_head => $product_Short_Desc_val){ ?>
                                  <ul>
                                      <span class="delete_desc_ul see-cursor">
                                      <input type="hidden" value="<?php echo $product_Short_Desc_head; ?>" class="pro_short_desc_delete">
                                      <i class="fa fa-trash" aria-hidden="true"></i></span>
                                      <li class="field"><span>Info Section Title</span><input type="text" name="pro_short_title[]" placeholder="e.g., Tech Specs" value="<?php echo $product_Short_Desc_head; ?>"></li>
                                      <li class="field"><span>Description</span><textarea rows="5" name="pro_short_desc[]" class="see_editor" id="text_<?php echo $i; ?>"><?php echo $product_Short_Desc_val; ?></textarea>
                                      <script> CKEDITOR.replace("text_<?php echo $i; ?>",{ filebrowserUploadUrl: "editor/upload.php", });</script>
                                      </li>
                                </ul>
                    <?php       $i++; }
                     } ?>
                                <!--</ul>-->
                            </div>
                        <?php } ?>
                            <div class="see-full">
                                <span class="add-short-desc-button add-btn"><i class="fa fa-plus"></i> Add an info section</span>
                            </div>
                            <div class="shortdesc">

                            </div>
                        </div>


                </div>

    <!------------------------------------------------ Custom Product Designing Section ------------------------------------ -->

                        <li class="field active-deactive see-lh-28 bold-font" style="display:none">
                            <label>Custom Design?</label>
                            <div class="active-deactive-btn ">
                                <input type="checkbox" name="custom_on"  <?=((!empty($cutomizeData) ? 'checked' : '')); ?> value="<?=((!empty($cutomizeData) ? '1' : '0')); ?>" class="custom_design_switch">

                                <span class="toggle-label" data-on="Yes" data-off="No"></span>
                                <span class="toggle-handle"></span>
                            </div>
                        </li>

                        <!-- Toggle Script -->
                        <link rel="stylesheet" href="<?php echo MANAGE_URL;?>css/customDesign.css">

                        <script>
                            $('.custom_design_switch').change(function(){

                                var designContent = $('.toggleDesign');

                                if($(this).prop('checked')) {
                                    designContent.show()
                                    $(this).attr('value', '1');
                                } else {
                                    designContent.hide()
                                }
                            });





                        </script>

     <!---------- Custom Design Section OPT HTML/PHP -->

    <div class="toggleDesign see-full shadowbox see-padding-16 see-padding-left-right-24 see-round see-margin-bottom see-margin-top" <?=((empty($cutomizeData) ? 'style="display:none;"' : '')); ?>>

        <ul class="see-full see-padding-24 see-padding-bottom-0">

                        <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-padding-16 see-padding-top-0 see-border-bottom">
                            <h3 class="f-26">Customize Product</h3>
                        </div>

                            <div class="see-full">
                <!--		    	<span class="add-info-section-btn1 add-btn"><i class="fa fa-plus see-lh-22"></i> Add Option</span>-->
                            </div>
                           <?php if(!empty($getProductDetail)){ ?>
                               <div class="see-full">

    <!-----------------------------COPIED SECTION ----------------------------->

    <div class="lumise_tabs_wrapper lumise_form_settings" data-id="products">


                    <div class="lumise_tab_content" id="lumise-tab-design">
                        <div class="lumise_form_group lumise_field_stages">

                           <div class="lumise_form_content">
                                    <div class="lumise_tabs_wrapper" id="lumise-stages-wrp" data-id="stages">
                                         <ul class="lumise_tab_nav">

                                            <li class="active">
                                                <a href="#lumise-tab-front" data-label="Front">
                                                    <text>Front</text>
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            </li>

                                            </ul>

                <div class="lumise_tabs">

                    <div class="lumise_tab_content active" id="lumise-tab-front" data-stage="front">
                    <!-- Copied -->
                    <div class="lumise-stage-settings lumise-product-design stage-enabled" id="lumise-product-design-front">
                            <div class="lumise-stage-body">
                                <div class="lumise_form_content">
                                    <div class="toggle">
                                        <input type="checkbox" name="is_mask" checked="true" />
                                        <span class="toggle-label" data-on="Yes" data-off="No"></span>
                                        <span class="toggle-handle"></span>
                                    </div>
                                    <label>
                                    Use as mask product?
                                    </label>
                                </div>
                                <div class="lumise-stage-design-view">

                                <?php
                                    $finalImage = '';
                                    if(!empty($cutomizeData)){
                                        $cutomizeData = $cutomizeData[0];
                                        $zoneVal = $cutomizeData['front'];
                                        $getEditZone = $zoneVal['edit_zone'];
                                        $getImgData = $zoneVal['img_detail'];
                                        $getCustomURL = $zoneVal['url'];
                                        $getCustomSource = $zoneVal['source'];
                                        $getCustomOverlay = $zoneVal['overlay'];
                                        $getCustomProductWidth = $zoneVal['product_width'];
                                        $getCustomProductHeight = $zoneVal['product_height'];
                                        $getCustomLabel = $zoneVal['label'];
                                        //EDIT ZONE DATA
                                        if(!empty($getEditZone)){
                                            $zoneHeight = $getEditZone['height'];
                                            $zoneWidth = $getEditZone['width'];
                                            $zoneLeft = $getEditZone['left'];
                                            $zoneTop = $getEditZone['top'];
                                            if($zoneLeft != 0){$positionZone = 'left: '.$zoneLeft.'px; top: '.$zoneTop.'px';}
                                            else{$positionZone = ''; }
                                            $zoneRadius = $getEditZone['radius'];
                                            $editZoneStyle = 'style="width: '.$zoneWidth.'px; height: '.$zoneHeight.'px; border-radius: '.$zoneRadius.'px; '.$positionZone.'"';
                                            $editZoneSize = 'data-info="'.$zoneWidth.' x '.$zoneHeight.'"';
                                        }
                                        // IMAGE DATA
                                        if($getCustomSource == "uploads"){

                                            $customURL = $zoneVal['img_detail'];
                                            $imgName =  $customURL['url'];
                                            $type =     $customURL['type'];
                                            $path = '../images/custom/'.$imgName;

                                            $data = file_get_contents($path);
                                            $finalImage = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                        }
                                    }

                                ?>

                                    <img src="<?php if(!empty($finalImage)){ echo $finalImage; }else{echo "http://localhost/printall/core/raws/products/hat.png"; } ?>" data-url="<?php if(!empty($cutomizeData)){ echo $getCustomURL; }else{echo "products/hat.png"; } ?>" data-source="<?php if(!empty($cutomizeData)){ echo $getCustomSource; }else{echo "raws"; } ?>" class="lumise-stage-image" data-svg="" />
                                    <div class="lumise-stage-editzone" <?php if(!empty($getEditZone)){ echo $editZoneStyle; } ?>>

                                        <div class="editzone-funcs">
                                                <button data-func="clear-design" style="display:none;" data-label="Clear Design Template">
                                                    <i class="fa fa-eraser"></i>
                                                </button>
                                                <button data-func="move" data-label="Drag to move the edit zone" onclick="return false;">
                                                    <i class="fa fa-plus" <?php if(!empty($getEditZone)){echo $editZoneSize;} ?>>  </i>
                                                </button>
                                        </div>
                                        <i class="fa fa-expand" data-func="resize" title="Resize the edit zone" <?php if(!empty($getEditZone)){echo $editZoneSize;} ?>></i>

                                    </div>

                                    <div class="editzone-ranges">
                                                                            <div class="edr-row design-scale" style="display: none;">
                                            <label>Design scale:</label>
                                            <input type="range" min="10" max="200" value="" />
                                        </div>
                                                                            <div class="edr-row editzone-radius">
                                            <label>Editzone radius:</label>
                                            <input type="range" min="0" max="500" value="0" />
                                        </div>
                                    </div>

                                </div>
                                <div class="lumise-stage-btn">
                                    <button data-btn data-select-base="front">
                                        <i class="fa fa-th"></i>
                                        Product image								</button>

                                    <button data-btn data-delete-base="front">
                                        <i class="fa fa-times"></i>
                                        Delete stage								</button>
                                    <input type="hidden" name="old-product-upload-front" value="products/hat.png" />
                                    <input type="hidden" name="old-product-upload-front-source" value="raws" />
                                    <input type="hidden" name="product-upload-front" value="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Copy END -->
                    </div>

                </div>
                            </div>
                        </div>
        </div>
        </div>
                                   </div>

    <!------------------------------ COPIED END ------------------------------->
        </ul>
                                </div>
                        <?php  } ?>




    <!----------------------------------------- END Custom Product END -------------------------------------->

                <div class="see-full shadowbox see-padding-16 see-padding-left-right-24 see-round see-margin-bottom see-margin-top">
                    <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-padding-16 see-padding-top-0 see-border-bottom">
                        <h3 class="f-26">Product Images</h3>
                    </div>
                    <ul class="see-full see-padding-24 see-padding-bottom-0">
                        <div class="see-full">
                            <li class="field thumbnail-img">

                                <div class="see-full upload-images">
                                   <ul id="imgSortable">
                                        <?php
                                        if(isset($_GET['type'])){
                                           if(!empty($product_media)){
											   $i = 1;
                                                foreach($product_media as $imgkey => $imgVal){
                                                    $proMediaId = $imgkey;
                                                    $Promedia_name = $imgVal['url'];
                                                    $promedia_alt = $imgVal['alt']; ?>
                                                    <li img-id="<?=$imgkey;?>" img-url="<?=$Promedia_name;?>" img-alt="<?=$promedia_alt;?>" >
                                                        <span class="remove-image"><img src="<?php echo SITE_PATH.'products/'.$Promedia_name.'?width=160'; ?>" alt="<?php echo $Promedia_name; ?>">
                                                        <label><a class="orange-button deleteimg" data="<?php echo $proMediaId; ?>-<?=$product_ID;?>">DELETE</a></label>
                                                        <input type="text" class="see-border-1" name="imgAlt_<?=$i; ?>" value="<?=$imgVal['alt'];?>">
                                                        </span>
                                                    </li>
                                                <?php
													$i++;
                                                }
                                             }
                                         } ?>
                                    </ul>
                                </div>
                                <div class="see-full see-margin-top">
                                    <div class="see-dp-inblock see-relative image-upload-btn">
                                        <input type="file" name="product_image[]" accepts="image/*" multiple id="gallery-photo-add">
                                        <span class="input-file-btn">Upload Image</span>
                                    </div>
                                </div>
                                <label class="input-label">Supported files svg, png, jpg, jpeg. Max size 1MB</label>
                            </li>
                        </div>
                    </ul>
                </div>

                <div class="see-4 see-ltb-4 see-tb-4 see-sm-12 see-xsm-12">
                    <li class="field">
                        <span>SKU</span>
                        <input type="text" name="productSKU" value="<?php if(!empty($getProductDetail)){ echo $product_sku; } ?>">
                    </li>
                </div>
                <div class="see-4 see-ltb-4 see-tb-4 see-sm-12 see-xsm-12">
                    <li class="field status">
                        <span>Stock</span>
                        <input type="number" name="productQuantity" value="<?php if(!empty($getProductDetail)){ echo $product_qty; } ?>">
                    </li>
                </div>
            
            <li class="field active-deactive see-lh-28 bold-font">
                <label>Partial Payment</label>
                <div class="active-deactive-btn ">
                    <input type="checkbox" name="isBook_on" value="<?=(($product_partial) ? '1' : '0'); ?>" class="isCod_switch" <?=((!empty($product_partial) ? 'checked' : ''));?> >
                    <span class="toggle-label" data-on="Yes" data-off="No"></span>
                    <span class="toggle-handle"></span>
                </div>
            </li>
            <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 isCod_div" <?=((empty($product_partial) ? 'style="display:none;"' : '')); ?>>
                <li class="field see-6">
                    <span>Partial Ammount</span>
                    <input type="num" name="partialprice" id="partialprice" value="<?php echo ((!empty($product_partial) && $partial_price) ? $partial_price : 0 );?>" />
                </li>
            </div>

            <li class="field active-deactive see-lh-28 bold-font">
                <label>COD Available?</label>
                <div class="active-deactive-btn ">
                    <input type="checkbox" name="isPin_on" value="<?=((!empty($product_pincode) ? '1' : '0')); ?>" class="isCod_switch" <?=((!empty($product_pincode) ? 'checked' : '')); ?>>
                    <span class="toggle-label" data-on="Yes" data-off="No"></span>
                    <span class="toggle-handle"></span>
                </div>
            </li>

            <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 isCod_div" <?=((empty($product_pincode) ? 'style="display:none;"' : '')); ?>>
                <li class="field see-6">
                    <span>Pincode Group</span>
                    <select name="pincode-group" id="pincode-group">
                        <?php
                        // Fetch Pincode Groups
                        $Pincodegroups = $see->getrows("see_pincode_group");
                        foreach($Pincodegroups as $pg){
                            $gid = $pg['see_pg_id'];
                            $groupName = $pg['see_pg_name']; ?>
                            <option value="<?php echo $gid; ?>" <?php echo  ((!empty($product_pincode) && $getProductDetail['see_pro_pincode'] == $gid) ? 'selected' : '');?>><?=$groupName;?></option>
                        <?php
                        } ?>
                    </select>
                </li>
                <li class="field see-6">
                    <span>COD Price</span>
                    <input type="num" name="pincode-price" id="pincode-price" value="<?php echo  ((!empty($product_pincode) && $getProductDetail['see_pro_pincode_price']) ? $getProductDetail['see_pro_pincode_price'] : 0 );?>" />
                </li>
            </div>
            <div class="see-3 see-ltb-3 see-tb-6 see-sm-12 see-xsm-12">
                <li class="field">
                    <span>Dimension Group (Product Length)</span>
                    <select name="dimension-group" id="dimension-group">
                    <?php
                        // Fetch Pincode Groups
                        $dimensionGroup = $see->getrows("see_dimension");
                        foreach($dimensionGroup as $dm){
                            $dmid = $dm['see_dm_id'];
                            $groupName = $dm['see_dm_name']; ?>
                             <option value="<?php echo $dmid; ?>" <?php echo  ((!empty($getProductDetail) && $getProductDetail['see_pro_dimension'] == $dmid) ? 'selected' : '');?>><?=$groupName;?></option>
                             <?php
                        } ?>
                    </select>
                </li>
            </div>
            <div class="see-3 see-ltb-3 see-tb-6 see-sm-12 see-xsm-12">
                <li class="field">
                    <span>Product Type</span>
                    <select name="product-type" id="product-type">
                    <?php
                        // Fetch Pincode Groups
                        $typesgroup = $see->getrows("see_product_type");
                        foreach($typesgroup as $pt){
                            $ptid = $pt['see_pt_id'];
                            $ptName = $pt['see_pt_name']; ?>
                             <option value="<?php echo $ptid; ?>" <?php echo  ((!empty($getProductDetail) && $getProductDetail['see_pro_type'] == $ptid) ? 'selected' : '');?>><?=$ptName;?></option>
                             <?php
                        } ?>
                    </select>
                </li>
            </div>


<!------------------------- TAGS / SEO / BRAND SECTION START -------------------->
            <div class="see-full shadowbox see-padding-16 see-padding-left-right-24 see-round see-margin-bottom see-margin-top">
                <div class="see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-padding-16 see-padding-top-0 see-border-bottom">
                    <h3 class="f-26">TAGS SYSTEM</h3>
                </div>
                <ul class="see-full see-padding-24 see-padding-bottom-0">
                    <div class="see-full">
                    <!-- Form Content Start -->
                        <li class="field see-6">
                            <span>Tags</span>
                            <input id="product_tags" type="text" name="product_tags" placeholder="e.g., nice seller, 2019 fashion, specially designed" />
                        </li>
                        <li class="field see-6">
                            <span>Brand</span>
                            <input id="product_brands" type="text" name="product_brands" placeholder="e.g., Maxima, Action, See Latest, Joon Corporation etc" />
                        </li>
                    <!-- Content End -->
                    </div>
                </ul>
            </div>
            <!------------------------- TAGS / SEO / BRAND SECTION END -------------------->
            <textarea style="display: none;" class="stages-field" name="stages"></textarea>
            
            
             <?php  
            $pagemetatitle = isset($updateseo['meta_title']) ? $updateseo['meta_title'] : '';
            $pagemetadesc = isset($updateseo['meta_desc']) ? $updateseo['meta_desc'] : '';
            $pagemetakeyword = isset($updateseo['meta_keywords']) ? $updateseo['meta_keywords'] : '';?>
 
              <li class="table-heading see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12 see-padding-0">
                <ul class="see-padding-0">
                    <li class="table-heading see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                        <label>Meta Title </label>
                        <div class="field">
                            <input class="count-letter" id="metat" type="text" name="page_meta_title" placeholder="Enter Meta Title" value="<?php echo $pagemetatitle; ?>" >
                        </div>
                    </li>
                    <li class="instruction"><label class="circle numbers">0</label><span>Characters: Most search engines use a maximum of 60 chars for the title</span></li>
                    <li class="table-heading see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                        <label>Meta  Description</label>
                        <div class="field">
                            <input type="text" class=" count-description" id="metad"  name="page_meta_description" placeholder="Enter Meta Description" value="<?php echo $pagemetadesc; ?>">
                        </div>
                    </li>
                    <li class="table-heading see-12 see-ltb-12 see-tb-12 see-sm-12 see-xsm-12">
                        <label>Meta  Keyword</label>
                        <div class="field">
                            <input type="text" class=" count-description" id="metad"  name="page_meta_keyword" placeholder="Enter Meta Keyword" value="<?php echo $pagemetakeyword; ?>">
                        </div>
                    </li>
                </ul>
            </li> 
            
            <ul class="see-12 see-margin-top">
                <input type="hidden" id="prouniqueid" name="prouniqueid" value="<?php if(!empty($getProductDetail)){ echo $product_ID; } ?>">
                <input name="queryType" type="hidden" value="<?php if(isset($_GET['type'])){ echo "update"; }else{ echo "new"; } ?>">
                <li class="field">
                    <input type="submit" name = "save_product" value="Save Product">
                </li>
            </ul>
            
    
            
            
        </form>
    </div>
</div>
        <div class=" manage-popup">
            <div class="popup-box">
                <ul>
                    <form method="post">
                        <li class="field">
                            <span>Product Option</span>
                            <input type="text" name="opt_name" id="opt_name"/>
                        </li>

                        <li class="field">
                            <span>Option Values</span>

                            <input id="opt_value" type="text" name="opt_value" placeholder="e.g., red, blue, black">
                        </li>


                        <li class="field">
                            <span></span>
                            <input id="add_options" type="submit" name="add_options" value ="add option">
                        </li>
                    </form>

                </ul>
            </div>
        </div>

         
        <div class="overlay-black"></div>
        <div class="product-edit-popup">
            <div class="frame">
                <div class="popup-head">
                    <h3>Edit Products Option</h3>
                    <span class="close-edit-product"><i class="fa fa-times"></i></span>
                </div>
                <div class="see-full see-padding-24 see-padding-left-right-24 popupedit">
                    <div class="see-full see-margin-bottom">
                        <label class="see-full see-padding-4">Option Name</label>
                        <input type="text" name="" class="see-full see-border-1 see-round see-padding-8 see-padding-left-right-12" placeholder="<?php echo $key; ?>" readonly>
                    </div>
                    <div class="see-full see-margin-bottom">
                        <label class="see-full see-padding-4">Choices for this option</label>
                        <div class="see-full see-border-1 see-round see-padding-8 see-padding-left-right-12 row-px-50">
                            <span class="label">
                                <span class="see-dp-inblock col-px-10 row-px-10 see-circle see-border-1" style="background: red"></span>
                                Red
                                <i class="fa fa-times see-lh-24"></i>
                                </span>
                            <span class="label">
                                <span class="see-dp-inblock col-px-10 row-px-10 see-circle see-border-1" style="background: green"></span>
                                green
                                <i class="fa fa-times see-lh-24"></i>
                            </span>
                            <span class="label">
                                <span class="see-dp-inblock col-px-10 row-px-10 see-circle see-border-1" style="background: black"></span>
                                black
                                <i class="fa fa-times see-lh-24"></i>
                            </span>
                        </div>
                    </div>
                    <div class="see-full see-text-right see-margin-top">
                        <input type="submit" name="" class="cancel" value="Cancel">
                        <input type="submit" name="" class="submit" value="Apply">
                    </div>
                </div>
        </div>
    </div>


        <div id="lumise-popup" style="display: none;">
                <div class="lumise-popup-content">
                    <header><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                        <h3>
                            <span>Select from default images, or upload your custom image</span>
                                                    <button class="lumise-btn-primary" data-act="upload">
                                <i class="fa fa-cloud-upload"></i>
                                Upload your image						</button>
                            <small>Accept file type: .jpg, .png, .svg (1KB -> 5MB)</small>
                            <input type="file" class="hidden" id="lumise-product-upload-input" />
                                                </h3>
                        <span class="close-pop"><svg enable-background="new 0 0 32 32" height="32px" id="close" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M17.459,16.014l8.239-8.194c0.395-0.391,0.395-1.024,0-1.414c-0.394-0.391-1.034-0.391-1.428,0  l-8.232,8.187L7.73,6.284c-0.394-0.395-1.034-0.395-1.428,0c-0.394,0.396-0.394,1.037,0,1.432l8.302,8.303l-8.332,8.286  c-0.394,0.391-0.394,1.024,0,1.414c0.394,0.391,1.034,0.391,1.428,0l8.325-8.279l8.275,8.276c0.394,0.395,1.034,0.395,1.428,0  c0.394-0.396,0.394-1.037,0-1.432L17.459,16.014z" fill="#121313" id="Close"></path><g></g><g></g><g></g><g></g><g></g><g></g></svg></span>
                    </header>
                    <div id="lumise-base-images">
                        <p class="lumise-notice">Notice: If you want the upload product image have the ability to change color on the editor. <a href="https://docs.lumise.com/product-mask-image/" target="_blank">Read more Mask Image <i class="fa fa-arrow-circle-o-right"></i></a></p>
                        <ul class="lumise-stagle-list-base">
                            <li><img data-act="base" data-src="products/bag_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/bag_back.png" /><span>bag back</span></li><li><img data-act="base" data-src="products/bag_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/bag_front.png" /><span>bag front</span></li><li><img data-act="base" data-src="products/basic_tshirt_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/basic_tshirt_back.png" /><span>basic tshirt back</span></li><li><img data-act="base" data-src="products/basic_tshirt_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/basic_tshirt_front.png" /><span>basic tshirt front</span></li><li><img data-act="base" data-src="products/basic_women_tshirt_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/basic_women_tshirt_back.png" /><span>basic women tshirt back</span></li><li><img data-act="base" data-src="products/basic_women_tshirt_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/basic_women_tshirt_front.png" /><span>basic women tshirt front</span></li><li><img data-act="base" data-src="products/cup_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/cup_back.png" /><span>cup back</span></li><li><img data-act="base" data-src="products/cup_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/cup_front.png" /><span>cup front</span></li><li><img data-act="base" data-src="products/shoe.png" data-source="raws" src="http://localhost/printall/core/raws/products/shoe.png" /><span>shoe</span></li><li><img data-act="base" data-src="products/hat.png" data-source="raws" src="http://localhost/printall/core/raws/products/hat.png" /><span>hat</span></li><li><img data-act="base" data-src="products/pillow.png" data-source="raws" src="http://localhost/printall/core/raws/products/pillow.png" /><span>pillow</span></li><li><img data-act="base" data-src="products/hoodie_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/hoodie_back.png" /><span>hoodie back</span></li><li><img data-act="base" data-src="products/hoodie_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/hoodie_front.png" /><span>hoodie front</span></li><li><img data-act="base" data-src="products/hoodies_sweatshirt_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/hoodies_sweatshirt_back.png" /><span>hoodies sweatshirt back</span></li><li><img data-act="base" data-src="products/hoodies_sweatshirt_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/hoodies_sweatshirt_front.png" /><span>hoodies sweatshirt front</span></li><li><img data-act="base" data-src="products/kids_babies_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/kids_babies_back.png" /><span>kids babies back</span></li><li><img data-act="base" data-src="products/kids_babies_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/kids_babies_front.png" /><span>kids babies front</span></li><li><img data-act="base" data-src="products/long_sleeve_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/long_sleeve_back.png" /><span>long sleeve back</span></li><li><img data-act="base" data-src="products/long_sleeve_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/long_sleeve_front.png" /><span>long sleeve front</span></li><li><img data-act="base" data-src="products/phone_case.png" data-source="raws" src="http://localhost/printall/core/raws/products/phone_case.png" /><span>phone case</span></li><li><img data-act="base" data-src="products/premium_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/premium_back.png" /><span>premium back</span></li><li><img data-act="base" data-src="products/premium_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/premium_front.png" /><span>premium front</span></li><li><img data-act="base" data-src="products/stickers.png" data-source="raws" src="http://localhost/printall/core/raws/products/stickers.png" /><span>stickers</span></li><li><img data-act="base" data-src="products/tank_tops_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/tank_tops_back.png" /><span>tank tops back</span></li><li><img data-act="base" data-src="products/tank_tops_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/tank_tops_front.png" /><span>tank tops front</span></li><li><img data-act="base" data-src="products/v_neck_tshirt_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/v_neck_tshirt_back.png" /><span>v neck tshirt back</span></li><li><img data-act="base" data-src="products/v_neck_tshirt_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/v_neck_tshirt_front.png" /><span>v neck tshirt front</span></li><li><img data-act="base" data-src="products/women_tank_tops_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/women_tank_tops_back.png" /><span>women tank tops back</span></li><li><img data-act="base" data-src="products/women_tank_tops_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/women_tank_tops_front.png" /><span>women tank tops front</span></li><li><img data-act="base" data-src="products/women_tshirt_back.png" data-source="raws" src="http://localhost/printall/core/raws/products/women_tshirt_back.png" /><span>women tshirt back</span></li><li><img data-act="base" data-src="products/women_tshirt_front.png" data-source="raws" src="http://localhost/printall/core/raws/products/women_tshirt_front.png" /><span>women tshirt front</span></li>					</ul>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                var lumise_upload_url = '<?=SITE_URL;?>images/order/',
                    lumise_assets_url = 'http://localhost/printall/core/';
            </script>


        <script type="text/javascript">


        window.URL = window.URL || window.webkitURL;
        window.lumise_create_thumbn = function(ops) {

            var img = new Image();
                img.onload = function(){

                    var cv = window.creatThumbnCanvas ?
                             window.creatThumbnCanvas :
                             window.creatThumbnCanvas = document.createElement('canvas');

                    cv.width = (ops.width ? ops.width : (ops.height*(this.naturalWidth/this.naturalHeight)));
                    cv.height = (ops.height ? ops.height : (ops.width*(this.naturalHeight/this.naturalWidth)));

                    var ctx = cv.getContext('2d'),
                        w = this.naturalHeight*(cv.width/this.naturalWidth) >= cv.height ?
                            cv.width : this.naturalWidth*(cv.height/this.naturalHeight),
                        h = w == cv.width ? this.naturalHeight*(cv.width/this.naturalWidth) : cv.height,
                        l = w == cv.width ? 0 : -(w-cv.width)/2,
                        t = h == cv.height ? 0 : -(h-cv.height)/2;

                    ctx.fillStyle = ops.background? ops.background : '#fff';
                    ctx.fillRect(0, 0, cv.width, cv.height);
                    ctx.drawImage(this, l, t, w, h);

                    ops.callback(cv.toDataURL('image/jpeg', 100));

                    delete ctx;
                    delete cv;
                    delete img;

                }

            img.src = ops.source;

        };

    $(document).ready(function() {

        $(".attr_type").change(function(){
           var coption = $(this).val();

        switch(coption){
                    case 'text':

                    $(this).parents('.attribData').find('.sub_attr').hide();
                        break;

                    case 'textarea':
                  $(this).parents('.attribData').find('.sub_attr').hide();
                        break;

                    case 'file':
                $(this).parents('.attribData').find('.sub_attr').hide();
                        break;

                    case 'select':
                $(this).parents('.attribData').find('.sub_attr').show();
                        break;

                    case 'radio':
                $(this).parents('.attribData').find('.sub_attr').show();
                        break;

                    case 'checkbox':
                $(this).parents('.attribData').find('.sub_attr').show();
                        break;

            default: ''
            };
        });


        $(".old_add_more").click(function(){
            var vid = $(this).attr('name');

            var fieldHTML = '<div class="add_field"><div class="see-6 see-ltb-6 see-tb-6 see-sm-12 see-xsm-12"><li class="field"><input type="text" placeholder = "Enter Dropdown name" name="old_subtype' + vid + '[]"></li></div><div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12"><li class="field"><input type="text" placeholder = "Enter Price" name="old_subprice' + vid + '[]"></li></div><div class="see-3 see-ltb-3 see-tb-3 see-sm-12 see-xsm-12"><li class="field"><a href="javascript:void(0);" class="delete_uls"><i class="fa fa-trash see-text-right see-red-text see-lh-24"></i></a></li></div></div></div>';
            $(this).parent().closest('ul').find('ul.see-full > .sub-attribute').append(fieldHTML);

             $(".delete_uls").click(function(){
           $(this).closest('.add_field').remove();
        });


    });

        $(".delete_uls").click(function(){
           $(this).closest('.add_field').remove();
        });
    /*     $('.sect').each(function(i){
             Vcount++;

             var oldID = $(this).find("input").attr('name');
                    $(this).find("input").attr('name', oldID + i + '[]');
        });*/





        $('.add-info-section-btn1').click(function(){
            var arr = $('#cat_name').val();
            if(arr == ''){
                alert("Please select category First");
            }else{
                                $("#opt_name").tokenInput("prodAjax.php?cid=" + arr, {
                                queryParam: "qname",
                                tokenLimit: 1,
                                preventDuplicates: false,
                            onResult: function(results){
                                $.each(results, function (index, value) {
                                    value.name = value.see_op_name;
                                    value.id = value.see_op_name;
                                });

                                return results;
                            }
                        });
                $("#token-input-opt_name").blur(function(){
                    var spid = $("#opt_name").val();

                                $("#opt_value").tokenInput("prodAjax.php?spid=" + spid, {
                                queryParam: "qval",
                                preventDuplicates: true,
                                onResult: function(results){
                                $.each(results, function (index, value) {
                                    value.name = value.see_val;
                                    value.id = value.see_val;
                                });

                                return results;
                            }
                        });

                });



              }
        });


        // Customize Product Option - Load Changes from DB





    });
    var enjson = function(str) {
        return btoa(encodeURIComponent(JSON.stringify(str)));
    }
    var dejson = function(str) {
        return JSON.parse(decodeURIComponent(atob(str)));
    };

    $('.lumise_tab_nav li').click(function(e){
        e.preventDefault();
        var currentTab = $(this);
        var oldTab = $('.lumise_tab_nav li.active');

        oldTab.removeClass('active');
        var oldTabLink = oldTab.children('a').attr('href');


        currentTab.addClass('active');



    });



    $('.lumise-stage-editzone').on('mousedown', function(e){

        var func = e.target.getAttribute('data-func') ||
                    e.target.parentNode.getAttribute('data-func');

        var gui = $(e.target).hasClass('.editzone-gui') ||
                  $(e.target).closest('.editzone-gui').length > 0;

        if (func != 'move' && func != 'resize' && gui !== true)
            return false;


    var $this = $(this),
    clientX = e.clientX,
    clientY = e.clientY,
    left = this.offsetLeft,
    top = this.offsetTop,
    width = this.offsetWidth,
    height = this.offsetHeight,
    limit = true,
    etarget = $(e.target),
    resize = e.target.getAttribute('data-func') == 'resize' ||
    e.target.parentNode.getAttribute('data-func') == 'resize';


    $(document).on('mousemove', function(e){


                                var pw = $this.parent().width();
                                    ph = $this.parent().height() - $this.parent().find('.editzone-ranges').height();


                                if (resize) {

                                    var new_width = (width+(e.clientX-clientX)),
                                        new_height = (height+(e.clientY-clientY));

                                    if (new_width < 30)
                                        new_width = 30;

                                    if (new_height < 50)
                                        new_height = 50;

                                    if (new_width > pw-left)
                                        new_width = pw-left;

                                    if (new_height > ph-top)
                                        new_height = ph-top;

                                    new_width = Math.round(new_width);
                                    new_height = Math.round(new_height);

                                    $this.css({
                                        width: new_width+'px',
                                        height: new_height+'px'
                                    });

                                    etarget.attr({'data-info': new_width+' x '+new_height});

                                }else{
                                    var new_left = (left+(e.clientX-clientX)),
                                        new_top = (top+(e.clientY-clientY));

                                    if (limit) {

                                        if (new_left < 0)
                                            new_left = 0;

                                        if (new_top < 0)
                                            new_top = 0;

                                        if (new_left > pw-width)
                                            new_left = pw-width;

                                        if (new_top > ph-height)
                                            new_top = ph-height;

                                    }else{

                                        if (new_left < -width*0.85)
                                            new_left = -width*0.85;

                                        if (new_top < -height*0.85)
                                            new_top = -height*0.85;

                                        if (new_left > pw-(width*0.15))
                                            new_left = pw-(width*0.15);

                                        if (new_top > ph-(height*0.15))
                                            new_top = ph-(height*0.15);

                                    };

                                    $this.css({
                                        left: new_left+'px',
                                        top: new_top+'px'
                                    });

                                    etarget.attr({'data-info': new_top+' x '+new_left});

                                }
             e.preventDefault();
                            });

                            $(document).on('mouseup', function(){
                                $(document).off('mousemove mouseup');
                            });


            });

        $('button[data-select-base]').click(function(e){

            $('#lumise-popup').show().data({stage: this.getAttribute('data-select-base')});
            e.preventDefault();

        });



        $('button[data-delete-base]').click(function(e){
            var s = this.getAttribute('data-delete-base'),
            wrp = $('#lumise-product-design-'+s);
            wrp.find('img.lumise-stage-image').attr({'src': '', 'data-url': ''});
            wrp.find('div.lumise-stage-editzone').hide();
            wrp.removeClass('stage-enabled').addClass('stage-disabled');
            e.preventDefault();
        });

        $('.lumise-popup-content .close-pop').click(function(){
            $('#lumise-popup').hide();
            e.preventDefault();
        });

        $('#lumise-popup').click(function(e){

            var act = e.target.getAttribute('data-act');
            if (e.target.id == 'lumise-popup')
                                act = 'close';

                            if (!act)
                                return;

                            switch (act) {
                                case 'close':
                                    $(this).hide();
                                    return e.preventDefault();
                                break;
                                case 'base':

                                    var url = e.target.getAttribute('data-src'),
                                        source = e.target.getAttribute('data-source');

                                    set_image(url, source);


                                break;
                                case 'upload':

                                    var file_input = $('#lumise-product-upload-input'),
                                        fi = file_input.get(0);
                                    if (fi === undefined)
                                        return e.preventDefault();

                                    fi.type = '';
                                    fi.type = 'file';
                                    fi.click();

                                    if (!file_input.data('onprocess')) {

                                        file_input.data({onprocess: true}).on('change', function(){

                                            var f = this.files[0];

                                            if (["image/png", "image/jpeg", "image/svg+xml"].indexOf(f.type) === -1)
                                                return alert("Please upload valid Image file PNG/JPG");
                                            else if (f.size < 1024)
                                                return alert("Image file is too small, Please upload higher resolution");
                                            else if (f.size > 5024000)
                                                return alert("Image File is too large, Please upload average size");

                                            var reader = new FileReader();

                                            reader.addEventListener("load", function(){

                                                var result = svguni(this.result);
                                                var data = {
                                                    data: result,
                                                    size: f.size,
                                                    name: 'lumise-base-'+f.name.replace(/[^0-9a-zA-Z\.\-\_]/g, "").trim().replace(/\ /g, '+'),
                                                    type: f.type ? f.type : f.name.split('.').pop()
                                                }, stage = $('#lumise-popup').data('stage');

                                                set_image(this.result, 'uploads');

                                                $('input[name="product-upload-'+stage+'"]').val(JSON.stringify(data));

                                                delete reader;
                                                delete f;
                                                delete data;

                                            }, false);

                                            reader.readAsDataURL(f);

                                        });

                                    }

                                    e.preventDefault();

                                break;
                            }

        });




            function svguni(data) {
                if (data.indexOf('image/svg+xml') === -1)
                    return data;

                data = data.split(',');
                data[1] = $('<div>'+atob(data[1].replace('viewbox=', 'viewBox='))+'</div>');

                data[1].find('[id]').each(function(){
                    this.id = this.id.replace(/[\u{0080}-\u{FFFF}]/gu,"");
                });

                var svg = data[1].find('svg').get(0);

                if (!svg.getAttribute('width'))
                    svg.setAttribute('width', '1000px');

                if(!svg.getAttribute('height')){
                    var vb = svg.getAttribute('viewBox').trim().split(' ');
                    svg.setAttribute('height', (1000*(parseFloat(vb[3])/parseFloat(vb[2])))+'px');
                };

                data[1] = btoa(data[1].html());

                return data[0]+','+data[1];

            }


        function set_image(url, source) {

                var stage = $('#lumise-popup').hide().data('stage'),
                wrp = $('#lumise-product-design-'+stage);

                if (url.indexOf("image/svg+xml") > -1 || url.split('.').pop() == 'svg')
                    wrp.find('img.lumise-stage-image').attr({'data-svg': '1'});
                else wrp.find('img.lumise-stage-image').attr({'data-svg': ''});

                var _url = url;

                if (source == 'raws') {
                    _url = lumise_assets_url+'raws/'+url;
                    wrp.find('input[name="is_mask"]').get(0).checked = true;
                }else{
                    wrp.find('input[name="is_mask"]').get(0).checked = false;
                    if (url.indexOf('data:image/') === -1)
                    _url = lumise_upload_url+url;
                }
                if (url.indexOf('data:image/') === 0)
                    url = '[blob-'+new Date().getTime().toString(36)+']';

                    wrp.find('img.lumise-stage-image').attr({
                    'src': _url,
                    'data-url': url,
                    'data-source': source
                });

                    wrp.find('div.lumise-stage-editzone').show().css({left: '', top: '', height: '', width: ''});
                    wrp.addClass('stage-enabled').removeClass('stage-disabled');

                if (wrp.find('.lumise-stage-image').height() <= 280) {
                    wrp.find('div.lumise-stage-editzone').css({
                    top: '10px',
                    height: (wrp.find('.lumise-stage-image').height()-20)+'px'
                    });
                };

                if (wrp.find('.lumise-stage-image').width() <= 175) {
                    wrp.find('div.lumise-stage-editzone').css({
                    left: '10px',
                    width: (wrp.find('.lumise-stage-image').width()-20)+'px'
                    });
                };


        }

            $('input[type=range]').on('input', function(){
                var s = $(this).closest('.lumise-stage-design-view').find('.lumise-stage-editzone').css({
                                borderRadius: this.value+'px'
                            });
            });


            $('.editzone-ranges .design-scale').on('input', function(e){

                var img = $(this).closest('.lumise-stage-design-view').find('.design-template-inner img');
                if (img.length === 0)
                return;

                var im = img.get(0),
                w = im.naturalWidth,
                h = im.naturalHeight,
                cl = im.offsetLeft+(im.offsetWidth/2),
                ct = im.offsetTop+(im.offsetHeight/2);

                img.css({
                width:	((w*this.value)/100)+'px',
                height:	((h*this.value)/100)+'px',
                left:	(cl-(((w*this.value)/100)/2))+'px',
                top:	(ct-(((h*this.value)/100)/2))+'px'
                });

            });



                        $("#PrdForm").on("submit", function(e){


                            var data = {};

                            var has_stage = false;
                            $('#lumise-stages-wrp .lumise_tab_content img.lumise-stage-image').each(function(){
                                if (this.getAttribute('data-url') !== '')
                                    has_stage = true;
                            });

                            if (has_stage === false) {

                                return false;
                            };

                            var temp_op = false;
                            if ($('#lumise-tab-design').css('display') != 'block') {
                                temp_op = true;
                                $('#lumise-tab-design').css('display', 'block');
                            };

                            $('#lumise-stages-wrp .lumise_tab_content').each(function(){
                                if (this.style.display != 'block') {
                                    this.style.display = 'block';
                                    this.setAttribute('data-hidden', 'true');
                                }
                            });

                            $.map($('#lumise-stages-wrp .lumise_tab_content'), function(tab) {

                                tab.style.display = 'inline-block';

                                var url = $(tab).find('img.lumise-stage-image').data('url'),
                                    source = $(tab).find('img.lumise-stage-image').data('source'),
                                    overlay = $(tab).find('input[name="is_mask"]').get(0).checked;

                                if (url !== '') {

                                    if (url.indexOf('data:image/') === 0)
                                        url = '[blob-'+new Date().getTime().toString(36)+']';

                                    var ret = {},
                                        b = $(tab).find('.lumise-stage-design-view').get(0),
                                        c = $(tab).find('.lumise-stage-design-view .editzone-ranges').get(0),
                                        l = $(tab).find('.lumise-stage-editzone').get(0),
                                        templ = {},
                                        stg = tab.getAttribute('data-stage');

                                    if (
                                        $(tab).find('.design-template-inner').length > 0 &&
                                        $(tab).find('.design-template-inner').data('id')
                                    ) {
                                        var im = $(tab).find('.design-template-inner img').get(0);
                                        templ = {
                                            id: $(tab).find('.design-template-inner').data('id'),
                                            scale: $(tab).find('.design-scale input').val(),
                                            css: $(tab).find('.design-template-inner img').attr('style'),
                                            offset: {
                                                top: im.offsetTop,
                                                left: im.offsetLeft,
                                                width: im.offsetWidth,
                                                height: im.offsetHeight,
                                                natural_width: im.naturalWidth,
                                                natural_height: im.naturalHeight
                                            }
                                        };
                                    };

                                    data[stg] = {
                                        edit_zone: {
                                            height: l.offsetHeight,
                                            width: l.offsetWidth,
                                            left: l.offsetLeft-(b.offsetWidth/2)+(l.offsetWidth/2),
                                            top: l.offsetTop-((b.offsetHeight-c.offsetHeight)/2)+(l.offsetHeight/2),
                                            radius: $(tab).find('.editzone-radius input').val(),
                                        },
                                        url: url,
                                        source: source,
                                        overlay: overlay,
                                        product_width: b.offsetWidth,
                                        product_height: (b.offsetHeight-c.offsetHeight),
                                        template: templ,
                                        label: $('#lumise-stages-wrp .lumise_tab_nav a[href="#lumise-tab-'+stg+'"]').data('label')
                                    }
                                }


                                tab.style.display = '';
                              console.log(data);
                            });




                            $('.lumise_tab_content[data-hidden="true"]').hide();

                            $('textarea[name="stages"]').val(JSON.stringify(data));

                            if (temp_op === true)
                                $('#lumise-tab-design').css('display', 'none');

                            $('.lumise_field_printing').each(function(){
                                var vals = {};
                                $(this).find('.lumise_checkbox').each(function(){
                                    if ($(this).find('input.action_check').prop('checked')) {
                                        var v = $(this).find('input.action_check').val();
                                        vals[v] = '';
                                        if (this.getAttribute('data-type') == 'size') {
                                            vals[v] = $(this).next('.lumise_radios').find('input[type="radio"]:checked').val();
                                        }
                                    }
                                });
                                $(this).find('input.field-value').val(JSON.stringify(vals));
                            });

                            return true;

             });


            $('.isCod_switch').change(function(){
                    var isCodDiv = $('.isCod_div');
                    if($(this).prop('checked')) {
                        isCodDiv.show()
                        $(this).attr('value', '1');
                    } else {
                        isCodDiv.hide()
                    }
                });


    $(function(){
        $("#sortable").sortable({
    update: function(event, ui) {
        $('.attr-content').each(function(i) {
            var inputNewVar = $(this).find('.subattrz_type');
            inputNewVar.attr('name', 'old_subtype'+i+'[]');

            var inputNewVar = $(this).find('.subattrz_price');
            inputNewVar.attr('name', 'old_subprice'+i+'[]');
        });
    }
});
            $("#sortable").disableSelection();
    } );


$(function(){
        $(".sortableAttr").sortable();
        $(".sortableAttr").disableSelection();
 });


    $(function(){
        $("#imgSortable").sortable({
            axis: 'y',
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                var newImg = new Array();
                $(this).children('li').each(function(){
                    var sortid = $(this).attr('img-id');
                    var sorturl = $(this).attr('img-url');
                    var sortalt = $(this).find('input').val();
                    var sortdata = {type: "image", url: sorturl, alt: sortalt, sort: sortid};
                    newImg.push(sortdata);
                });

                // POST to server using $.post or $.ajax
                $.ajax({
                    data:{
                        imgsort:newImg,
                        imgpid: <?=$getProductDetail['see_pro_id'];?>
                    },
                    type: 'POST',
                    url: '<?=MANAGE_URL;?>sortAjax.php',
                    success: function(response){
    alert(response);

                    }
                });
            }
        });
            $("#imgSortable").disableSelection();
    });

    // accordioin js
$(document).ready(function(){
    $('.accordion li:first-child .attr-name .arrow').toggleClass('fa-angle-up fa-angle-down')
    $('.attr-name').click(function(){
      $(this).closest('li').siblings().find('.attr-content').slideUp();
      $(this).nextAll().slideToggle();
      $(this).find('.arrow').toggleClass('fa-angle-down fa-angle-up')

    })

});


function attrupdate(){
    var attrData = new Array();
    $('.add-attr-section ul li').each(function(){
        $(this).find('input').each(function(){
            alert("sdad");
            attrData.push(inputName);
        });


    console.log(attrData);
})
}

$('.ac_butupdate').on('click',function(){
    /*attrupdate();*/
});

// TAG SYSTEM Jquery Codes Start
$(document).ready(function(){ 
function addtoken(list){ $("#product_tags").tokenInput('add',list) }

$("#product_tags").tokenInput("<?=MANAGE_URL;?>ajax/tagsAjax.php?type=tag", {
    <?php if(!empty($getTags)){?>
    prePopulate: [
        <?php if(!empty($getTags)){ foreach($getTags as $tag){?>
                    {id: "<?=$tag['see_t_name'];?>", name: "<?=$tag['see_t_name'];?>"},
                <?php } ?>
                ],
       <?php }
    } ?>
    queryParam: "q",
        onResult: function(results){
        $.each(results, function (index, value) {
    	var pval = value.see_t_name
    	list = pval.split(',')
    	if(value.see_t_id=="9854765210" && list.length>1){
    	    for(i=0;i<list.length;i++){ addtoken({name:list[i],id:list[i]}) }
    	}
    	else{
    	    value.name = value.see_t_name;
            value.id = value.see_t_id;
    	}
    });
    return results;
    }
});


$("#cat_data").tokenInput("<?=MANAGE_URL;?>ajax/catAjax.php", {
    <?php if(!empty($catearray)){?>
    prePopulate: [
        <?php if(!empty($catearray)){ 
            foreach($catearray as $catd){
                $getcat = $see->getrowcolumn('see_category',['see_c_id','see_c_name'],"see_c_id = '$catd'"); ?>
                    {id: "<?=$getcat['see_c_id'];?>", name: "<?=$getcat['see_c_name'];?>"},
                <?php } ?>
                ],
       <?php }
    } ?>
    queryParam: "q",
        onResult: function(results){
        $.each(results, function (index, value) {
    	var pval = value.see_c_name
    	list = pval.split(',')
    	if(value.see_t_id=="9854765210" && list.length>1){
    	    for(i=0;i<list.length;i++){ addtoken({name:list[i],id:list[i]}) }
    	}
    	else{
    	    value.name = value.see_c_name;
            value.id = value.see_c_id;
    	}
    });
    return results;
    }
});

    $("#product_brands").tokenInput("<?=MANAGE_URL;?>ajax/tagsAjax.php?type=brand", {
            <?php if(!empty($getBrands)){?>
    prePopulate: [
        <?php if(!empty($getBrands)){ foreach($getBrands as $brand){?>
                    {id: "<?=$brand['see_t_id'];?>", name: "<?=$brand['see_t_name'];?>"},
                <?php } ?>
                ],
       <?php }
                      } ?>
        queryParam: "q",
        preventDuplicates: true,
        onResult: function(results){
            $.each(results, function (index, value) {
                value.name = value.see_t_name;
                value.id = value.see_t_id;
            });
            return results;
        }
    });
});


$(document).on(`click`,`.deleteimg`,function(){
    let mediadata = $(this);
    let media = $(this).attr(`data`);
    if(media.length > 0){
        $.get(`ajax/deleteproductimg.php`,{media:media},(res)=>{
            mediadata.closest('li').remove();
        })
    }
})



        </script>
