
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body>
    
    <section style="background-color: #3083DC; color:white; font-weight:bold;  " class="mt-3 mx-5 rounded shadow-lg p-4 mb-4">
       
    <div class="d-flex justify-content-center">
            <form action="factura.php" method="POST" autocomplete="off">

                <h2>Generador de Factura</h2> 
                <!-- Nombre -->

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>

                <!-- Identificación -->
                <div class="mb-3">
                    <label for="address" class="form-label">Dirección</label><br>
                    <input type="text" class="form-control" id="address" name="address" >
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label for="number" class="form-label">Teléfono</label> <br>
                    <input type="text" class="form-control" id="number" name="number" >
                </div>

                <div class="mb-3 d-flex justify-content-center">
                    <button type="submit" name="send_user_info" class="btn btn-dark"> Guardar Información </button>
                </div>
                
                <div class="mb-3">
                    <label for="product" class="form-label">Producto</label><br>
                    <input type="text" class="form-control" id="product" name="product" >
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Cantidad</label> <br>
                    <input type="number" class="form-control" id="amount" name="amount" >
                </div>

                <div class="mb-3">
                    <label for="value" class="form-label">Valor</label> <br>
                    <input type="number" class="form-control" id="value" name="value" >
                </div>

                <div class="mb-3 d-flex justify-content-center">
                    <button type="submit" name="add_product" class="btn btn-dark">Añadir producto</button> 
                </div>
                
                <div class="mb-3 d-flex justify-content-center">
                    <button type="submit" name="restart-products" class="btn btn-warning">Borrar todos los productos</button>
                </div>
            </form>
        </div>
    </section>


    <?php
        function showUserInfo($name,$address,$number){ 
                echo "Señor (a): " . $name . "<br>";
                echo "Dirección: " . $address . "<br>";
                echo "Telefono: " . $number . "<br>";
                
            }
            
        function showDate(){
            echo "NIT: " . rand(10000,1000000) . "<br>";
            date_default_timezone_set('America/Bogota');
            echo "Fecha de Factura: ". date("j") . " / " . date("m") . " / " . date("Y") . "<br>"; 
            echo "Fecha de vecimiento: " ?> <input type="date">
    <?php } ?>

    <?php

        function addUserInfo(&$usersArray,$name, $lastName, $id, $email){ 

            array_push($usersArray, array( "identificacion"=> $id, "nombre" => $name, "apellido" => $lastName, "correo" => $email));
        }

        function validateText($text){  // Función que valida si el texto ingresado es correcto
            if (!empty($text)){
                if (is_numeric($text)){  //Si se ingresan números
                    return false;
                }else{
                    $carac_permitidos ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZáéíóú";
                    $text = str_replace(' ','',$text); //Remove the spaces in the entered text
                    for($i = 0; $i < strlen($text); $i++){
                        if (strpos($carac_permitidos, substr($text,$i,1)) === false){ // Si es un caracter no permitido
                            return false;
                        }
                    }
                    return true;
                }
            }
        }

        function validateNum($num){
            if (is_numeric($num)){
                return true;
            }else{
                return false;
            }
        }

        session_start();

        if (isset($_POST['product'])){
            $product = $_POST['product'];

        }

        if (isset($_POST['amount'])){
            $amount = $_POST['amount'];


        }

        if (isset($_POST['value'])){
            $value = $_POST['value'];

        }

        if(isset($_POST['send_user_info'])){
                if (isset($_POST['name']) && isset($_POST['number']) && validateText($_POST['name']) && validateNum($_POST['number'])){
                    $_SESSION['name'] = $_POST['name'];
                    $_SESSION['address'] = $_POST['address'];
                    $_SESSION['number'] = $_POST['number'];
                } ?>
                <div class="mx-5 ">
                    <div class="border rounded border-5 p-3" style="text-align: center;">
                        <?php showUserInfo($_SESSION['name'], $_SESSION['address'], $_SESSION['number']);
                            showDate(); ?>
                    </div>
                </div>
        <?php }

        if ( isset($_POST["add_product"])){ 
            if (validateText($product) && validateNum($amount)){ ?>

                <div class="mx-5 ">
                    <div class="border rounded border-5 p-3" style="text-align: center;">
                        <?php showUserInfo($_SESSION['name'], $_SESSION['address'], $_SESSION['number']);
                            showDate(); ?>
                        
                    </div>
                </div>
                
                <?php

                if(isset($_SESSION['added_products'])){
                    array_push($_SESSION['added_products'], array('amount' => $amount, 'product' => $product, 'value' => $value * $amount));
                }else{
                    $_SESSION['added_products'][] = array('amount' => $amount, 'product' => $product, 'value' => $value * $amount);
                }

            }else{
                echo "Campos incompletos y/o datos ingresados no corresponden. " . "<br>";
            }
        }

        if (isset($_POST["restart-products"])){
            $_SESSION["added_products"] = array();
            $_SESSION['subtotal'] =0;
        }
        
    ?>

    <?php if (!empty($_SESSION["added_products"])){ ?>
            <br><br><br>
            <?php
            var_dump($_SESSION['added_products']); ?>
            <div class="mx-5">
                <table style="text-align: center;" class="table table-bordered border border-5">
                    <thead> <!-- Cabecera -->
                        <tr> <!-- Fila -->
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody> <!-- Cuerpo de la tabla -->

                        <?php                  
                            for ($i = 0; $i < count($_SESSION['added_products']); $i++){?>
                            <tr>
                                <?php foreach($_SESSION['added_products'][$i] as $column => $value){ ?>
                                    <td> <?php echo $_SESSION['added_products'][$i][$column]; ?> </td>
                                    
                                <?php } ?>
                    <?php   } ?>
                            
                            </tr>      
                    </tbody>
                </table>
            
                <table class="table d-flex justify-content-end">
                    <tbody>
                        <tr>
                            <td><p><strong>Subtotal</strong></p></td>
                            
                                <?php 
                                $_SESSION['subtotal'] = 0;
                                for ($i = 0; $i < count($_SESSION['added_products']); $i++){ 
                                    $_SESSION['subtotal'] += $_SESSION['added_products'][$i]['value'];
                                } ?>
                                <td> <?php echo $_SESSION['subtotal']; ?></td>
                        </tr>
                        <tr>
                            <td><p><strong>IVA</strong></p></td>
                            <td><p>19 %</p></td>
                        </tr>
                        <tr>
                            <td><p><strong>Total</strong></p></td>
                            <td>
                                <p><?php echo $_SESSION['subtotal'] * 1.19 ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>                   

    <?php } ?>
</body>
</html>


