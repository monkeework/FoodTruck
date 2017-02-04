<?php
require 'food.php';
include 'includes/header.php';
//echo '<pre>';
//echo var_dump($_POST);
//echo '</pre>';
//check if the input is valid


/*
if(!isset($_POST["items"])) {
    echo '<div class = "col-md-4 col-md-offset-4">

              <h5 class="errorMsg">Undecided what you want to order? We suggest you order one of each!</h5>
       <a href = "index.php">Take me back</a>';
}
*/


//if the input is valid - if not send back to start
// If user has to click 'back' they might be annoyed, best practice is redirect automatically, then inform.
// see
if(!isset($_POST["items"])) {
    //send user back if empty
    header('Location: index.php?act=error');
}
else {

//loop through the $_POST array and create an array of Food objects the user ordered
for ($i = 0; $i < count($_POST["items"]); $i++) {

    //store object parameters from the $_POST array into variables
    $type = $_POST["items"][$i];
    $quantity = $_POST["quantity"][$i];
    //check if toppings were selected
    if(isset($_POST["topping" . $i])){
        $toppings = $_POST["topping" . $i];
    }
    else{
        $toppings = [];
    }

    /** @var array $foodOrder stores the ordered food items*/
    $foodOrder[] = new Food($type, $quantity, $toppings);

}


//echo '<pre>';
//echo var_dump($foodOrder);
//echo '</pre>';

//create the order summary showing all the items and toppings ordered,
//the subtotal for each item, and a cumulative total cost due.
$total = 0;
foreach ($foodOrder as $food) {
    echo '<div class = "orderSummary menuItem col-md-6 col-md-offset-3">
              
              <h5 class="foodName">' . $food->name . ' x ' . $food->quantity . '</h5>
              <p class="foodName cost">$' . $food->CalculatePerItemSubtotal() . ' </p>
              <button type="button" class="btn details"><i class="fa fa-chevron-down"></i></button>
             <div class = "priceDetails hide" >
              <p>Base price:(' . $food->price . ' /each)</p>
              <p class="cost">$' . $food->CalculateBasePrice() . ' </p>';

    //don't display toppings price if no toppings were selected

    if($toppings != []) {
        echo '
              <p>+' . implode(", ", $food->toppings) . '(' . $food->CalculateToppingsCost() . ' /each) </p>
              <p class="cost">$' . $food->CalculateToppingsCostTotal() . '  </p>';
    }

    echo '
              
<!-- added by Ayumi 2/3-->
              <!-- <p>Subtotal before tax (' . $food->quantity . ' orders): </p>
              <p class="cost">$' . $food->CalculateSubtotalBeforeTax() . ' </p>-->
              
              <p>Tax (9.6%)</p>
              <p class="cost">$' . $food->CalculateTax() . ' </p>
              <hr>
              <p>Subtotal:</p>
              <p class="cost">$' . $food->CalculatePerItemSubtotal() . ' </p>             
              </div>
        </div>';
    //calculate total

    $total += $food->CalculatePerItemSubtotal();
}

//display total
echo '<div id="finalPrice" class = "orderSummary menuItem col-md-6 col-md-offset-3">
    <h5 class="total">Total price:</h5>
    <p class="total cost">$' . number_format($total, 2) . ' </p>
    </div>';
}
include 'includes/footer.php';