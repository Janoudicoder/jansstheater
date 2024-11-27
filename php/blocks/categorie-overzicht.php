<?php
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);
$catBlock = the_field('categorie', 'block', $blockId);

$sqlCatCols = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlCatCols->bind_param('s', $catBlock);
$sqlCatCols->execute();
$sqlCatCols->store_result();
$sqlCatCols->bind_result($idCatItem);

// Check aantal resultaten
$numRows = $sqlCatCols->num_rows;
?>
<div id="block-<?=$blockId;?>" class="blocken w-full mx-auto relative team-lijst mt-32 mb-16">
    <div class="w-full md:w-2/3">
        <?php if ($titleBlock) { ?>
            <h2 class="text-[38px] text-center pb-10"><?= $titleBlock; ?></h2>
        <?php } ?>
        <?php if ($textBlock) { ?>
            <span class="block"><?= strip_tags($textBlock); ?></span>
        <?php } ?>
    </div>
    <div class="overflow-hidden relative">
        <?php if ($numRows >= 5) { // Slider als er 5 of meer items zijn ?>
        <div id="slider-container" class="flex space-x-4">
            <?php
            while ($sqlCatCols->fetch()) {
                $titleCatItem = the_field('item2', $idCatItem);
                $textCatItem = the_field('tekst', $idCatItem);

                echo '<a class="relative team diensten-block overflow-hidden flex-shrink-0 w-1/4" href="' . get_link($idCatItem, 'team') . '/">';
                echo '<div class="absolute bottom-0 z-10 flex w-full flex-col gradient justify-between pt-32 p-2 text-white">';
                echo getImg(get_url(), $idCatItem, 0, get_taal(), 'team', '500', '550', '500', 0);

                echo '<span class="font-bold text-xl mt-8 mb-4">' . $titleCatItem . '</span>';
                echo '<span style="width: 190px; height: 75px;">';
                echo '<span class="block font-bold text-sm"></span>';
                echo '<span class="text-sm">' . strip_tags(limit_text($textCatItem, 100)) . '</span>';
                echo '</span>';
                echo '</div>';
                echo '</a>';
            }
            ?>
        </div>
        <?php } else { // Statische weergave voor minder dan 5 items ?>
        <div class="flex flex-wrap justify-center space-x-4">
            <?php
            while ($sqlCatCols->fetch()) {
                $titleCatItem = the_field('item2', $idCatItem);
                $textCatItem = the_field('tekst', $idCatItem);

                echo '<a class="relative team diensten-block w-1/4 mb-4" href="' . get_link($idCatItem, 'team') . '/">';
                echo '<div class="flex flex-col items-center text-center p-4">';
                echo getImg(get_url(), $idCatItem, 0, get_taal(), 'team', '500', '550', '500', 0);

                echo '<span class="font-bold text-xl mt-4">' . $titleCatItem . '</span>';
                echo '<span class="text-sm mt-2">' . strip_tags(limit_text($textCatItem, 100)) . '</span>';
                echo '</div>';
                echo '</a>';
            }
            ?>
        </div>
        <?php } ?>
    </div>
</div>
<?php
// Sluit de statement
$sqlCatCols->close();
?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Slider Functionality
    const slider = document.getElementById('slider-container');
    if (slider) {
        const items = Array.from(slider.children);
        const itemWidth = items[0].offsetWidth + 16; // Include margin (space-x-4 adds 1rem or 16px)
        let index = 0;

        // Clone the items for a seamless loop
        const clonedItems = items.map(item => item.cloneNode(true));
        clonedItems.forEach(item => slider.appendChild(item)); // Append cloned items at the end

        // Set the slider to scroll smoothly and continuously
        const startAutoScroll = () => {
            let scrollWidth = index * itemWidth;
            slider.style.transition = 'transform 0.7s ease';
            slider.style.transform = `translateX(-${scrollWidth}px)`;

            index++;
            if (index >= items.length) {
                index = 0; // Restart without jump, so no empty space
            }
        };

        setInterval(startAutoScroll, 2000); // Auto-scroll every 2 seconds

        // Ensure seamless scroll without reset
        slider.addEventListener('transitionend', () => {
            if (index >= items.length) {
                slider.style.transition = 'none'; // Disable transition during reset
                slider.style.transform = 'translateX(0)'; // Reset to start immediately
                index = 0; // Start from the first item again
                setTimeout(() => {
                    slider.style.transition = 'transform 0.7s ease'; // Re-enable transition
                });
            }
        });
    }

    // Apply Random Colors to Teams
    const teams = document.querySelectorAll('.team');
    const colors = ['#D03184', '#FFC20E', '#3f3927', '#8A3486'];
    const usedColors = [];

teams.forEach((team, index) => {
    let availableColors = colors.filter(color => !usedColors.includes(color));

    // Ensure the previous color is not repeated
    if (usedColors.length > 0 && availableColors.includes(usedColors[usedColors.length - 1])) {
        availableColors = availableColors.filter(color => color !== usedColors[usedColors.length - 1]);
    }

    if (availableColors.length === 0) {
        usedColors.length = 0; // Reset if all colors are used
        availableColors = colors.slice();
    }

    const backgroundColor = availableColors[Math.floor(Math.random() * availableColors.length)];
    usedColors.push(backgroundColor);

    const borderColors = colors.filter(color => color !== backgroundColor);
    const borderColor = borderColors[Math.floor(Math.random() * borderColors.length)];

    team.style.backgroundColor = backgroundColor;
    team.style.borderRight = `4px solid ${borderColor}`;
    team.style.borderBottom = `4px solid ${borderColor}`;
});

});


</script>
