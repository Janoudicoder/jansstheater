<?php
$sqlCat = $mysqli->prepare("SELECT id,item2,tekst,paginaurl FROM siteworkcms WHERE hoofdid = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlCat->bind_param('s', get_id()); 
$sqlCat->execute();
$sqlCat->store_result();
$sqlCat->bind_result($idCat, $titelCat, $tekstCat, $urlCat);

$colors = ['#3a2b33', '#3f3927', '#FFC20E', '#FFC20E', '#3a2b33', '#3f3927']; // Background colors
$borderColors = ['#3f3927', '#FFC20E', '#D03184', '#D03184', '#3f3927', '#FFC20E']; // Border colors
$svgFiles = ['Dj Mixer.svg', 'Bagpipes.svg', 'Saxophone.svg', 'Guitar.svg', 'Dj Mixer.svg', 'Trumpet.svg']; // Icon files

if($sqlCat->num_rows > 0){ ?>
    <section class="bg-LichtGrijs">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 py-16 categorieen-grid-3">
            <?php
            $colorIndex = 0;
            $svgIndex = 0; // Initialize SVG index to track the order
            while ($sqlCat->fetch()) {
                $backgroundColor = $colors[$colorIndex];
                $borderColor = $borderColors[$colorIndex]; // Use the same index for border color
                $colorIndex = ($colorIndex + 1) % count($colors); // Cycle through the background colors
                
                // Use the current svg file based on the svgIndex
                $currentSVG = $svgFiles[$svgIndex];

                echo "<a class=\"relative w-[322px] h-[326px] flex flex-col cat-item pb-6\" href=\"".get_link($idCat)."\" 
                        style=\"background-color: {$backgroundColor}; border-right: 4px solid {$borderColor}; border-bottom: 4px solid {$borderColor};\">";
                    echo "<div class=\"img-container overflow-hidden\">";
                        getImg(get_url(), get_id(), '', get_taal(), 'uitgelicht', '321.54', '326', '321.54', 1);
                    echo "</div>";
                    echo "
                        <div class=\"catContent flex flex-col justify-between grow p-6 text-white text-sm\">
                          
                            <!-- Display icon according to the order in the array -->
                            <h3 class=\"font-bold text-lg mt-auto\">
                            <img src=\"/fa/webfonts/{$currentSVG}\" alt=\"" . pathinfo($currentSVG, PATHINFO_FILENAME) . "\" class=\"h-auto w-[40px] ml-0 pb-4 mx-auto\">
                                {$titelCat}
                            </h3>
                        </div>
                    ";
                echo "</a>";

                // Move to the next icon in the array (reset to 0 after last)
                $svgIndex = ($svgIndex + 1) % count($svgFiles);
            }
            ?>
        </div>
    </section>
<?php } ?>
