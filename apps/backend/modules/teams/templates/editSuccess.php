<h3><?php echo __("Edit Team"); ?>: <?php echo $team->getName(); ?></h3>
<hr/>
<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("teams_edit", $team); ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
        <?php foreach ($form as $name => $widget): ?>
            <?php if ($widget->isHidden()) continue; ?>
            <div class="control-group">
                <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                <div class="controls">
                    <?php if ($name == "seasons_list"): ?>
                        <?php echo $widget->render(null, array("style" => "width:300px;")); ?>
                    <?php else: ?>
                        <?php echo $widget->render(); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="control-group">
            <label class="control-label"><?php echo __("Players"); ?></label>
            <div class="controls">
                <table class="table table-striped table-bordered" id="players-table" style="width:auto;">
                    <thead>
                        <tr>
                            <th><?php echo __("SteamID64"); ?></th>
                            <th><?php echo __("Name"); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="players-body">
                    </tbody>
                </table>
                <button type="button" class="btn" id="add-player-btn"><i class="icon-plus"></i> <?php echo __("Add Player"); ?></button>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><?php echo __("Season"); ?></label>
            <div class="controls">
                <select name="seasons_list[]" multiple="multiple" style="width:auto;">
                    <?php foreach ($seasons as $season): ?>
                        <?php
                            $inarray = false;
                            for ($i=0;$i<count($currentSeasons); $i++) {
                                if ($season->getId() == $currentSeasons[$i]['season_id'])
                                    $inarray = true;
                            }
                            if ($inarray)
                                echo '<option selected="selected" value="' . $season->getId() . '">' . $season->getName() . '</option>';
                            else
                                echo '<option value="' . $season->getId() . '">' . $season->getName() . '</option>';
                        ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="<?php echo __("Edit Team"); ?>"/>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
(function() {
    var playerIndex = 0;
    var tbody = document.getElementById('players-body');
    var addBtn = document.getElementById('add-player-btn');

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function addPlayerRow(steamid64, name) {
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td><input type="text" name="players[' + playerIndex + '][steamid64]" value="' + escapeHtml(steamid64 || '') + '" placeholder="76561198000000000" /></td>' +
            '<td><input type="text" name="players[' + playerIndex + '][name]" value="' + escapeHtml(name || '') + '" placeholder="Player Name" /></td>' +
            '<td><button type="button" class="btn btn-danger btn-mini remove-player-btn"><i class="icon-trash icon-white"></i></button></td>';
        tbody.appendChild(tr);
        playerIndex++;
    }

    addBtn.addEventListener('click', function() {
        addPlayerRow('', '');
    });

    tbody.addEventListener('click', function(e) {
        var btn = e.target.closest('.remove-player-btn');
        if (btn) {
            btn.closest('tr').remove();
        }
    });

    <?php foreach ($currentPlayers as $player): ?>
    addPlayerRow(<?php echo json_encode($player['steamid64']); ?>, <?php echo json_encode($player['name']); ?>);
    <?php endforeach; ?>
})();
</script>