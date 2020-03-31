<?php
/**
 * @package boxapp_comparison_for_joomshopping
 * @author BoxApp Studio <info@boxapp.net>
 * @copyright Copyright © BoxApp. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version 1.0.0
 *
 * @var $this JshoppingViewComparison
 * @var $config jshopConfig
 * @var $products array
 * @var $product jshopProduct
 * @var $categoryExtraFields array
 * @var $showExtraFieldIds array
 */

JHtml::script('com_jshopping/comparison.js', false, true);
JHtml::stylesheet('com_jshopping/comparison.css', array(), true);
$config = $this->config;
$products = $this->products;
$categoryExtraFields = $this->categoryExtraFields;
$showExtraFieldIds = $this->showExtraFieldIds;
$extraFieldNames = !empty($products) ? array_keys($products[0]->extraFieldsByGroups) : array();
?>
<div class="jshop comparison_main_block" id="comjshop">
    <h1><?php echo(_JSHOP_COMPARISON_VIEW_TITLE); ?></h1>
    <? if (!empty($products)) : ?>
        <table class="comparison_table">
            <tr>
                <td <?php echo($this->showAddToCart ? 'rowspan="2"' : ''); ?> class="comparison_first_cell">
                    <?php if ($this->showFilter) : ?>
                        <form action="<?php echo($this->filterActionUrl); ?>" id="comparison_form" name="comparison_form" method="post">
                            <div class="comparison_filters">
                                <div class="comparison_filter_extra_fields">
                                    <div class="comparison_filter_extra_fields_inputs">
                                        <div class="comparison_filter_extra_fields_input">
                                            <label>
                                                <input type="radio" name="extra_fields_filter" value="0" <?php echo($this->extraFieldsFilter == 0 ? 'checked="checked"' : ''); ?> onchange="this.form.submit();" />
                                                <?php echo(_JSHOP_COMPARISON_EXTRA_FIELDS_FILTER_ALL); ?>
                                            </label>
                                        </div>
                                        <div class="comparison_filter_extra_fields_input">
                                            <label>
                                                <input type="radio" name="extra_fields_filter" value="1" <?php echo($this->extraFieldsFilter == 1 ? 'checked="checked"' : ''); ?> onchange="this.form.submit();" />
                                                <?php echo(_JSHOP_COMPARISON_EXTRA_FIELDS_FILTER_INTERSECT); ?>
                                            </label>
                                        </div>
                                        <div class="comparison_filter_extra_fields_input">
                                            <label>
                                                <input type="radio" name="extra_fields_filter" value="2" <?php echo($this->extraFieldsFilter == 2 ? 'checked="checked"' : ''); ?> onchange="this.form.submit();" />
                                                <?php echo(_JSHOP_COMPARISON_EXTRA_FIELDS_FILTER_DIFF); ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="comparison_filter_category">
                                    <? if (!empty($this->categories)) : ?>
                                        <select name="category_id" id="category_id" onchange="this.form.submit();">
                                            <?php foreach ($this->categories as $category) : ?>
                                                <option value="<?php echo($category->category_id); ?>" <?php echo(($category->category_id == $this->categoryId) ? 'selected="selected"' : ''); ?>><?php echo($category->name); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                    <div class="clear_comparison_block">
                        <a href="<?php echo($this->clearComparisonUrl); ?>" class="compare_filters_clear_link">
                            <?php echo(_JSHOP_COMPARISON_CLEAR_ALL_PRODUCTS); ?>
                        </a>
                    </div>
                </td>
                <?php foreach ($products as $product) : ?>
                    <td class="product_image">
                        <div class="product_main_image">
                            <a href="<?php echo($product->deleteFromComparisonUrl); ?>" class="remove_product" title=""></a>
                            <?php if ($product->image) : ?>
                                <a href="<?php echo($product->productLink); ?>" title="<?php echo(htmlspecialchars($product->productFullName)); ?>">
                                    <img src="<?php echo($product->image); ?>" alt="<?php echo(htmlspecialchars($product->productFullName)); ?>" title="<?php echo(htmlspecialchars($product->productFullName)); ?>" />
                                </a>
                            <?php else : ?>
                                <a href="<?php echo($product->productLink); ?>" title="<?php echo(htmlspecialchars($product->productFullName)); ?>">
                                    <img src="<?php echo($config->image_product_live_path); ?>/<?php echo($config->noimage); ?>" alt = "<?php echo(htmlspecialchars($product->productFullName)); ?>" />
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="product-name">
                            <a href="<?php echo($product->productLink); ?>" title="<?php echo(htmlspecialchars($product->productFullName)); ?>">
                                <?php echo(htmlspecialchars($product->productFullName)); ?>
                            </a>
                        </div>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php if ($this->showAddToCart) : ?>
                <tr>
                    <?php foreach ($products as $product) : ?>
                        <td>
                            <form name="product" method="post" action="<?php echo($this->byProductUrl); ?>" enctype="multipart/form-data" autocomplete="off">
                                <span class="comparison_product_price">
                                    <?php echo(formatprice($product->getPriceCalculate())); ?>
                                </span>
                                <input type="submit" class="comparison_button_buy" value="<?php echo(_JSHOP_BUY); ?>" />
                                <input type="hidden" name="product_comparison_key" id="product_comparison_key" value="<?php echo($product->productComparisonKey); ?>" />
                            </form>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endif; ?>
            <?php foreach ($categoryExtraFields as $categoryExtraField) : ?>
                <?php if (in_array(intval($categoryExtraField->id), $showExtraFieldIds)) : ?>
                    <?php if ($groupName != $categoryExtraField->groupname) : ?>
                        <?php $groupName = $categoryExtraField->groupname; ?>
                        <?php if ($this->showOptionsGroupName) : ?>
                            <tr><td colspan="<?php echo(count($products) + 1); ?>"><div class="options_group_name"><?php echo($groupName); ?></div></td></tr>
                        <?php endif; ?>
                    <?php endif; ?>
                    <tr>
                        <td>
                            <?php echo($categoryExtraField->name); ?>
                        </td>
                        <?php foreach ($products as $key => $product) : ?>
                            <td>
                                <?php foreach ($product->extraFields as $productFieldValue) : ?>
                                    <?php if (intval($categoryExtraField->id) === intval($productFieldValue['id'])) : ?>
                                        <?php echo($productFieldValue['value']); ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        <div class="clearfix"></div>
    <?php else : ?>
        <div class="empty_products">
            <?php echo(_JSHOP_COMPARISON_NO_PRODUCTS); ?>
        </div>
    <?php endif; ?>
</div>