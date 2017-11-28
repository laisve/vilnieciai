<?php
session_start();
require_once('connection.php');

include 'head.php';
?>
    <div class="edit">
        <div class="subhead">
            Redaguoti gyventojo duomenis
        </div>
        <div class="edit-background">
            <div class="edit-inner">
                <form method="post" action="">
                    <div class="edit-head">
                        <button type="button" class="btn btn-light" id="back">Grįžti</button>
                        <button class="btn btn-light" name="save" type="submit">Išsaugoti</button>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $_REQUEST['pid']; ?>">
                    <div class="form-group row">
                        <label for="gimimo_metai" class="col-md-3 col-sm-4 col-form-label">Gimimo metai</label>
                        <div class="col-sm-4">
                            <input type="text" readonly class="form-control-plaintext" id="gimimo_metai" value="<?php
                                    $qr = "SELECT gimimo_metai FROM gyventojai WHERE id=". $_REQUEST['pid'];
                                    $row = fetch_record($qr);
                                    $metai = $row['gimimo_metai'];
                                    echo $metai;
                                ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lytis" class="col-md-3 col-sm-4 col-form-label">Lytis</label>
                        <div class="col-sm-4">
                            <input type="text" readonly class="form-control-plaintext" name="lytis" value="<?php
                                    $qr = "SELECT lytis FROM gyventojai WHERE id=". $_REQUEST['pid'];
                                    $row = fetch_record($qr);
                                    $lytis = $row['lytis'];
                                    echo $lytis;
                                ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="seimos_padetis" class="col-md-3 col-sm-4 col-form-label">Šeimyninė padėtis</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="seimos_padetis"><?php
                                $qr = "SELECT seimos_padetis FROM gyventojai WHERE id=". $_REQUEST['pid'];
                                $row = fetch_record($qr);
                                $seimos_padetis = $row['seimos_padetis'];
                                
                                $qr1 = "SELECT DISTINCT(seimos_padetis) FROM gyventojai ORDER BY seimos_padetis ASC;";
                                $res = fetch_all($qr1);
                                
                                foreach($res as $r) {
                                    if($r == $seimos_padetis) {
                                        echo "<option selected>" . $r['seimos_padetis'] . "</option>";
                                    }
                                    else {
                                        echo "<option>" . $r['seimos_padetis'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="vaiku_sk" class="col-md-3 col-sm-4 col-form-label">Vaikų skaičius</label>
                        <div class="col-sm-4">
                            <input type="number" min="0" class="form-control text-right pull-right" name="vaiku_sk" placeholder="<?php
                                $qr = "SELECT vaikai FROM gyventojai WHERE id=". $_REQUEST['pid'];
                                $row = fetch_record($qr);
                                $vaiku_sk = $row['vaikai'];
                                echo $vaiku_sk;
                            ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="seniunija" class="col-md-3 col-sm-4 col-form-label">Seniūnija</label>
                        <div class="col-sm-4">
                            <input class="form-control seniunijos" type="text" name="seniunija" placeholder=<?php
                                $qr = "SELECT seniunija FROM gyventojai WHERE id=". $_REQUEST['pid'];
                                $row = fetch_record($qr);
                                $seniunija = $row['seniunija'];
                                echo $seniunija;
                            ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="gatve" class="col-md-3 col-sm-4 col-form-label">Gatvė</label>
                        <div class="col-sm-4">
                            <input class="form-control gatves" type="text" name="gatve" placeholder="<?php
                                $qr = "SELECT gatve FROM gyventojai WHERE id=". $_REQUEST['pid'];
                                $row = fetch_record($qr);
                                $gatve = $row['gatve'];
                                echo $gatve;
                            ?>">
                        </div>
                    </div>
                    <?php
                    if(isset($_POST['save'])) {
                        
                        $id = $_POST['id'];
                        $edit = [];
                        
                        if(!empty($_POST['seimos_padetis'])) {
                            $edit[] = "seimos_padetis = '". $_POST['seimos_padetis']. "'";
                            $where = '';
                        }
                        if(!empty($_POST['vaiku_sk'])) {
                            $edit[] = "vaikai = '". $_POST['vaiku_sk']. "'";
                            $where = '';
                        }
                        if(!empty($_POST['gatve'])) {
                            
                            $qr1 = "SELECT DISTINCT(ter_rej_kodas) FROM gyventojai WHERE gatve = '". escape_this_string($_POST['gatve']) . "'";
                            $res1 = fetch_record($qr1);
                            $ter_rej = $res1['ter_rej_kodas'];
                            
                            $qr2 = "SELECT DISTINCT(gatves_kodas) FROM gyventojai WHERE gatve = '". escape_this_string($_POST['gatve']) . "'";
                            $res2 = fetch_record($qr2);
                            $gat_kodas = $res2['gatves_kodas'];
                            
                            $qr3 = "SELECT DISTINCT(gatves_id) FROM gyventojai WHERE gatve = '". escape_this_string($_POST['gatve'])."'";
                            $res3 = fetch_record($qr3);
                            $gat_id = $res3['gatves_id'];
                                
                            $edit[] = "gatve = '". escape_this_string($_POST['gatve']) . "', ter_rej_kodas = '". $ter_rej ."', gatves_kodas = '". $gat_kodas . "', gatves_id = '". $gat_id ."'";
                            $where = '';
                            
                        }
                        if(!empty($_POST['seniunija'])) {
                            $edit[] = "seniunija = '". escape_this_string($_POST['seniunija']) . "'";
                            $where = '';
                        }
                        
                        if(count($edit) > 0) {
                            $where = implode(', ', $edit);
                        }
                        
                        $qr = "UPDATE gyventojai SET ". $where . ", updated_at = NOW() WHERE id = '$id';";
                        var_dump($qr);
                        
                        
                        if(mysqli_query($connection, $qr)) {
                            $_SESSION['success'] = "Duomenys sėkmingai atnaujinti";
                        }
                        else {
                            $_SESSION['error'] = "Nepavyko atnaujinti duomenų";
                        }
                        header("Location: index.php");
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script type="text/javascript" src="scripts.js"></script>
    
    <?php include 'select.php'; ?>
    
</body>
</html>