<?php
include_once ('config.php');

if(isset($_GET['i'])){
    $i=$_GET['i'];
}else{
    $i=0;
}
?>
<div class="panel panel-default subform_product_score">
    <div class="form-group">

        <label for="product<?php print $i ?>" class="col-sm-2 control-label">Produit</label>
        <div class="col-sm-4">
            <select name="product_score[<?php print $i ?>][product]" id ="product<?php print $i ?>" class="form-control">
                <option value="">Sélectionnez un produit</option>
                <?php if (count($aProducts)) { ?>
                    <?php
                    foreach ($aProducts as $aOneProduct) {
                        ?>    
                        <option value="<?php print $aOneProduct['PRODUCT_ID'] ?>"><?php print $aOneProduct['PRODUCT_LABEL'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>


    </div>
    <div class="form-group">
        <label  for="product_score<?php print $i ?>" class="col-sm-2 control-label">Score</label>
        <div class="col-sm-4">
            <select name="product_score[<?php print $i ?>][score]" id="product_score<?php print $i ?>" class="form-control">
                <option value="">Sélectionnez un score</option>
                <?php for ($counter = 0; $counter <= 10; $counter++) { $score = $counter / 10;  ?>
                    <option value="<?php print$score ?>"><?php print $score ?></option>
                <?php } ?>
            </select>

        </div>
    </div>
</div>