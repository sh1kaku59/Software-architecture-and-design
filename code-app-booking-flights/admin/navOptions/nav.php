<ul>
    <?php
    // Loop through the navigation options array
    foreach ($navOptions as $label => $link) {
        if (is_array($link)) {
            // Handle dropdown menus if the value is an array
            echo "<li class='dropdown'>$label";
            echo "<ul class='dropdown-content'>";
            foreach ($link as $subLabel => $subLink) {
                echo "<li><a href='$subLink?option=$subLabel'>$subLabel</a></li>";
            }
            echo "</ul></li>"; // Close the ul tag for the dropdown content
        } else {
            // Output single links
            echo "<li><a href='$link?option=$label'>$label</a></li>";
        }
    }
    ?>
</ul>
