<?php

/*
 * This file is part of the PHPLeague package.
 *
 * (c) Maxime Dizerens <mdizerens@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Variables
$id_player = ( ! empty($_GET['id_player']) ? (int) $_GET['id_player'] : 0);
$page_url  = admin_url('admin.php?page=phpleague_player&id_player='.$id_player);
$message   = array();
$data      = array();
$menu      = array(__('Player Information', 'phpleague') => '#', __('Player Record', 'phpleague') => '#');

// Security check
if ($db->is_player_unique($id_player) === TRUE)
    wp_die(__('We did not find the player in the database.', 'phpleague'));

// We edit the player basic information
if (isset($_POST['edit_player']) && check_admin_referer('phpleague'))
{
    // Secure data
    $firstname = (string) trim($_POST['firstname']);
    $lastname  = (string) trim($_POST['lastname']);
    $birthdate = (string) trim($_POST['birthdate']);
    $picture   = (string) trim($_POST['picture']);
    $desc      = (string) trim($_POST['description']);
    $weight    = (int) $_POST['weight'];
    $height    = (int) $_POST['height'];
    $country   = (int) $_POST['country'];
    $term      = (int) $_POST['term'];

    if ( ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate))
    {
       $birthdate = '0000-00-00';
       $message[] = __('The birthdate must follow the pattern: "YYYY-MM-DD".', 'phpleague');
    }
    
    if ($weight == 0 || $weight > 255)
    {
       $weight    = '0';
       $message[] = __('The weight must be bigger than 0 and lower than 255.', 'phpleague');
    }
    
    if ($height == 0 || $height > 255)
    {
       $height    = '0';
       $message[] = __('The height must be bigger than 0 and lower than 255.', 'phpleague');
    }

    // We need to pass those tests to insert the data
    if ($id_player === 0)
    {
       $message[] = __('Busted! ID is not correct!', 'phpleague');
    }
    elseif ($fct->valid_length($firstname, 3) === FALSE)
    {
       $message[] = __('The first name must be alphanumeric and 3 characters long at least.', 'phpleague');
    }
    elseif ($fct->valid_length($lastname, 3) === FALSE)
    {
       $message[] = __('The last name must be alphanumeric and 3 characters long at least.', 'phpleague');
    }
    else
    {
        $message[] = __('Player edited with success!', 'phpleague');
        $db->update_player(
            $id_player, $firstname, $lastname, $birthdate, $height, $weight, $desc, $picture, $country, $term
        );
    }
}
elseif (isset($_POST['player_history']) && check_admin_referer('phpleague')) // We update the player history
{
    // Secure data
    $data = ( ! empty($_POST['history'])) ? $_POST['history'] : NULL;

    if (is_array($data))
    {
        foreach ($data as $key => $item)
        {
            $db->update_player_history($id_player, $key, $item['number'], $item['id_position']);
        }
    }
    $message[] = __('Profile updated successfully.', 'phpleague');
}
elseif (isset($_POST['add_team']) && check_admin_referer('phpleague')) // We add one team in the player history
{
    // Secure data
    $id_team = ( ! empty($_POST['id_team'])) ? (int) $_POST['id_team'] : 0;

    if ($id_team === 0)
    {
        $message[] = __('No team has been selected!', 'phpleague');
    }
    elseif ($db->player_already_in_team($id_player, $id_team) === TRUE)
    {
        $message[] = __('A player cannot be twice in the same team.', 'phpleague');
    }
    else
    {
        $db->update_player_history($id_player, $id_team, 0, 0, 'insert');
        $message[] = __('Team added successfully to the profile.', 'phpleague');
    }
}

// Get countries list
foreach ($db->get_every_country(0, 250, 'ASC') as $array)
{
    $countries_list[$array->id] = esc_html($array->name);
}

// Get terms list
$tags_list[0] = __('-- Select a term --', 'phpleague');
foreach (get_tags(array('hide_empty' => FALSE)) as $tag)
{
    $tags_list[$tag->term_id] = esc_html($tag->name);
}

// -- Player information
$pics_list = $fct->return_dir_files(WP_PHPLEAGUE_UPLOADS_PATH.'players/');
$player    = $db->get_player($id_player);
echo '<div id="adminpanel-menu"><ul>';
echo '<li class="adminpanel-menu-li"><a href="#" class="adminpanel-menu-link" id="adminpanel-menu-1">'.__('Player Information', 'phpleague').'</a></li>';
echo '<li class="adminpanel-menu-li"><a href="#" class="adminpanel-menu-link" id="adminpanel-menu-2">'.__('Player Record', 'phpleague').'</a></li>';
echo '</ul></div><div id="adminpanel-content">';
if ( ! empty($message) && is_array($message))
{
    echo '<div class="updated">';
    foreach ($message as $note)
    {
        echo '<p>'.esc_html($note).'</p>';
    }
    echo '</div>';
}

echo '<div class="adminpanel-content-box" id="adminpanel-content-1">';
echo '<div class="section"><h3 class="heading">'.__('Player Information', 'phpleague').'</h3><div class="option"><div class="full">';
echo $fct->form_open($page_url);
echo
    '<table class="form-table">
        <tr>
            <td class="required">'.__('First Name:', 'phpleague').'</td>
            <td>'.$fct->input('firstname', esc_html($player->firstname)).'</td>
            <td class="required">'.__('Last Name:', 'phpleague').'</td>
            <td>'.$fct->input('lastname', esc_html($player->lastname)).'</td>
        </tr>
        <tr>
            <td>'.__('Height:', 'phpleague').'</td> 
            <td>'.$fct->input('height', (int) $player->height).'</td>
            <td>'.__('Weight:', 'phpleague').'</td>
            <td>'.$fct->input('weight', (int) $player->weight).'</td>
        </tr>
        <tr>
            <td>'.__('Birthdate:', 'phpleague').'</td>
            <td>'.$fct->input('birthdate', esc_html($player->birthdate)).'</td>
            <td class="required">'.__('Country:', 'phpleague').'</td>
            <td>'.$fct->select('country', $countries_list, (int) $player->id_country).'</td>
        </tr>
        <tr>
            <td>'.__('Picture:', 'phpleague').'</td>
            <td>'.$fct->select('picture', $pics_list, esc_html($player->picture)).'</td>
            <td>'.__('Term:', 'phpleague').'</td>
            <td>'.$fct->select('term', $tags_list, (int) $player->id_term).'</td>
        </tr>
    </table>';

$settings = array(
    'quicktags' => array('buttons' => 'em, strong, link',),
    'text_area_name' =>'description',
    'quicktags' => true,
    'tinymce' => true
);

wp_editor(esc_html($player->description), 'description', $settings);
echo '<div class="submit">'.$fct->input('id_player', $id_player, array('type' => 'hidden')).$fct->input('edit_player', __('Save', 'phpleague'), array('type' => 'submit')).'</div>';
echo $fct->form_close();
echo '</div><div class="clear"></div></div></div></div>';

// -- Add a new Team
$teams_list[0] = __('-- Select a Team --', 'phpleague');
foreach ($db->get_teams_from_leagues() as $team)
{
    $league = $team->league_name.' '.$team->league_year.'/'.($team->league_year + 1);
    $teams_list[$league][$team->team_id] = esc_html($team->club_name);
}
echo '<div class="adminpanel-content-box" id="adminpanel-content-2">';
echo '<div class="section"><h3 class="heading">'.__('Player Record', 'phpleague').'</h3><div class="option"><div class="full">';
echo $fct->form_open($page_url);
echo $fct->select('id_team', $teams_list);
echo __(' Select one team from a league.', 'phpleague');
echo $fct->input('add_team', __('Add', 'phpleague'), array('type' => 'submit', 'class' => 'button'));
echo $fct->form_close();

// -- Player history
$history = $db->get_player_history($id_player);
echo $fct->form_open($page_url);
echo
'<table class="widefat">
    <thead>
        <tr>
            <th>'.__('League', 'phpleague').'</th>
            <th>'.__('Team', 'phpleague').'</th>
            <th>'.__('Number', 'phpleague').'</th>
            <th colspan="2">'.__('Position', 'phpleague').'</th>
        </tr>
    </thead>
    <tbody>';
    
    // Display all the information...
    $positions_list[0] = __('-- Select a position --', 'phpleague');

    // Only display if we get an history...
    foreach ($history as $row)
    {
        // TODO - This is only a test..
        // Get positions list...
        foreach (PHPLeague_Sports_Football::$positions as $key => $value)
        {
            $positions_list[$key] = $value; 
        }

        echo '<tr id="'.$row->id_player_team.'"><td>'.esc_html($row->league).' '.$row->year.'/'.($row->year + 1).'</td>';
        echo '<td>'.esc_html($row->club).'</td>';
        echo '<td>'.$fct->input('history['.$row->id_team.'][number]', (int) $row->number, array('size' => 4)).'</td>';
        echo '<td>'.$fct->select('history['.$row->id_team.'][id_position]', $positions_list, (int) $row->position).'</td>';
        echo '<td>'.$fct->input('delete_player_team', __('Delete', 'phpleague'), array( 'type'  => 'button', 'class' => 'button delete_player_team')).'</td></tr>';
    }

echo '</tbody></table><div class="submit">';
echo $fct->input('player_history', __('Save Table', 'phpleague'), array('type' => 'submit')).'</div>'.$fct->form_close();
echo '</div><div class="clear"></div></div></div></div></div>';