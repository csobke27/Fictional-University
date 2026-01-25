<?php
require_once plugin_dir_path(__FILE__) . 'GetPets.php';
$getPets = new GetPets();
get_header(); ?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">Pet Adoption</h1>
    <div class="page-banner__intro">
      <p>Providing forever homes one search at a time.</p>
    </div>
  </div>  
</div>

<div class="container container--narrow page-section">
  <!-- search form -->
  <div class="pet-adoption-search-form-wrapper">
    <p>Use the form below to search for your perfect pet match!</p>
    <form class="pet-adoption-search-form" method="GET">
      <input type="text" name="species" placeholder="Species" value="<?php echo isset($_GET['species']) ? esc_attr($_GET['species']) : ''; ?>">
      <input type="number" name="minweight" placeholder="Min Weight" value="<?php echo isset($_GET['minweight']) ? esc_attr($_GET['minweight']) : ''; ?>">
      <input type="number" name="maxweight" placeholder="Max Weight" value="<?php echo isset($_GET['maxweight']) ? esc_attr($_GET['maxweight']) : ''; ?>">
      <input type="number" name="minyear" placeholder="Min Birth Year" value="<?php echo isset($_GET['minyear']) ? esc_attr($_GET['minyear']) : ''; ?>">
      <input type="number" name="maxyear" placeholder="Max Birth Year" value="<?php echo isset($_GET['maxyear']) ? esc_attr($_GET['maxyear']) : ''; ?>">
      <input type="text" name="favhobby" placeholder="Favorite Hobby" value="<?php echo isset($_GET['favhobby']) ? esc_attr($_GET['favhobby']) : ''; ?>">
      <input type="text" name="favcolor" placeholder="Favorite Color" value="<?php echo isset($_GET['favcolor']) ? esc_attr($_GET['favcolor']) : ''; ?>">
      <input type="text" name="favfood" placeholder="Favorite Food" value="<?php echo isset($_GET['favfood']) ? esc_attr($_GET['favfood']) : ''; ?>">
      <input class="pet-adoption-search-submit" type="submit" value="Search">
    </form>
  </div>
  <!-- end search form -->
  <p>This page took <strong><?php echo timer_stop();?></strong> seconds to prepare. Found <strong><?php echo number_format($getPets->resultCount); ?></strong> results (showing the first <?php echo count($getPets->pets); ?>).</p>
  <table class="pet-adoption-table">
    <tr>
      <th>Name</th>
      <th>Species</th>
      <th>Weight</th>
      <th>Birth Year</th>
      <th>Hobby</th>
      <th>Favorite Color</th>
      <th>Favorite Food</th>
      <?php if(current_user_can('administrator')) { ?>
      <th>Delete</th>
      <?php } ?>
    </tr>
    <?php foreach($getPets->pets as $pet) : ?>
      <tr>
        <td><?php echo esc_html($pet->petname); ?></td>
        <td><?php echo esc_html($pet->species); ?></td>
        <td><?php echo esc_html($pet->petweight); ?> lbs</td>
        <td><?php echo esc_html($pet->birthyear); ?></td>
        <td><?php echo esc_html($pet->favhobby); ?></td>
        <td><?php echo esc_html($pet->favcolor); ?></td>
        <td><?php echo esc_html($pet->favfood); ?></td>
        <?php if(current_user_can('administrator')) { ?>
        <td style="text-align: center;">
          <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="deletepet">
            <input type="hidden" name="petid" value="<?php echo esc_attr($pet->id); ?>">
            <button class="delete-pet-button" type="submit">X</button>
          </form>
        </td>
        <?php } ?>
      </tr>
    <?php endforeach; ?>
  </table>

  <?php
  if(current_user_can('administrator')) {
  ?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="create-pet-form" method="POST">
      <p>Enter just the name for a new pet. Its species, weight, and other details will be randomly generated.</p>
      <?php if (isset($_GET['error']) && $_GET['error'] === 'emptyname'){ ?>
        <p style="color: red;">Error: Pet name cannot be empty.</p>
      <?php } ?>
      <input type="hidden" name="action" value="createpet">
      <input type="text" name="incomingpetname" placeholder="New Pet Name...">
      <button type="submit">Add New Pet</button>
    </form>
  <?php
  }
  ?>
  
</div>

<?php get_footer(); ?>