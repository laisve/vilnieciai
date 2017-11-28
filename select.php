<script>
        $(document).ready(function() {
            GatvesSelect();
            SeniunijosSelect();
        });
        
        function GatvesSelect() {
            var Gatves = [<?php
            $query = "SELECT DISTINCT(gatve) FROM gyventojai ORDER BY gatve ASC;";
            $results = fetch_all($query);
            
            foreach($results as $row) {
                echo "'" . $row['gatve'] . "', ";
            }
            ?>];
            
            $('.gatves').autocomplete({
                source: Gatves,
                minLength: 3,
                scroll: true
            }).focus(function() {
                $(this).autocomplete("search", "");
            });
        }
        
        function SeniunijosSelect() {
            var Seniunijos = [<?php
            $query = "SELECT DISTINCT(seniunija) FROM gyventojai ORDER BY seniunija ASC;";
            $results = fetch_all($query);
            
            foreach($results as $row) {
                echo "'" . $row['seniunija'] . "', ";
            }
            ?>];
            
            $('.seniunijos').autocomplete({
                source: Seniunijos,
                minLength: 3,
                scroll: true
            }).focus(function() {
                $(this).autocomplete("search", "");
            });
        }
    </script>