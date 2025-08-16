<div class="clearfix"></div>

  <div id="header">
    <div class="container">
      <div class="navbar navbar-default yamm">
        <div class="navbar-header">
          <!-- Removed navbar-toggle for all screen sizes -->
          <a href="/" class="navbar-brand v3-logo-mobile">
            <img width="100" src="images/logo.png" alt="V3 Electricals Logo" class=" hidden-xs hidden-sm">
            <img width="70" style="max-width:70px;min-width:40px;width:30vw;" src="images/logo.png" alt="V3 Electricals Logo" class="hidden-md hidden-lg v3-logo-mobile-small">
          </a>
        </div>
        <div id="navbar-collapse-grid" class="navbar-menu-fullwidth pull-right"><!-- changed class to always show menu -->
          <!-- BEGIN V3 NAV -->
          <ul class="nav navbar-nav">
            <li><a href="/" <?php echo $current_page == 'index' ? 'class="active"' : ''; ?>>Mobile Home</a></li>
            <li><a href="about" <?php echo $current_page == 'about' ? 'class="active"' : ''; ?>>About</a></li>
            <li class="dropdown">
              <a href="services" class="dropdown-toggle" data-toggle="dropdown" <?php echo $current_page == 'services' ? 'class="active"' : ''; ?>>Services <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="electrical_fitouts">Electrical Fit-outs</a></li>
                <li><a href="external_ht_lt">Industrial HT & LT </a></li>
                <li><a href="solar_epc">Solar EPC</a></li>
                <li><a href="liaisoning">Liaisoning Works</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Projects <b class="caret"></b></a>
              <ul class="dropdown-menu"> 
                <li><a href="projects-completed">Completed Projects</a></li>
                <li><a href="projects-ongoing">Ongoing Projects</a></li>
              </ul>
            </li>
            <li><a href="team" <?php echo $current_page == 'team' ? 'class="active"' : ''; ?>>Team</a></li>
            <li><a href="contact" <?php echo $current_page == 'contact' ? 'class="active"' : ''; ?>>Contact us</a></li>
          </ul>
          <!-- END V3 NAV -->
        </div>
      </div>
      
    </div>
  </div>
<style>
.navbar {
  display: flex !important;
  align-items: center;
  justify-content: space-between;
  flex-wrap: nowrap;
  width: 100%;
  padding: 0;
}

.navbar-header {
  display: flex;
  align-items: center;
  flex: 0 0 auto;
  margin: 0;
  padding: 0;
}

.v3-logo-mobile {
  display: flex;
  align-items: center;
  margin: 0;
  padding: 0;
  width: auto;
  text-align: left;
}

.v3-logo-mobile img {
  max-width: 120px;
  width: 80vw;
  height: auto;
  display: inline-block;
  margin: 0;
}

.navbar-menu-fullwidth {
  display: flex !important;
  flex: 1 1 auto;
  flex-wrap: nowrap;
  justify-content: flex-end;
  align-items: center;
  min-width: 0;
}

.navbar-menu-fullwidth ul.navbar-nav {
  display: flex;
  flex-direction: row;
  gap: 10px;
  white-space: nowrap;
  margin: 0;
  padding: 0;
}

.navbar-nav > li {
  float: none;
  display: inline-block;
  position: relative;
}

/* Dropdowns on mobile: open on click */
.navbar-nav > li.dropdown > .dropdown-menu {
  display: none;
  position: absolute;
  left: 0;
  top: 100%;
  min-width: 180px;
  background: #fff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  z-index: 999;
}
/* 
@media (max-width: 991px) {
  .navbar-nav > li.dropdown > .dropdown-menu {
    left: 80%;
    right: auto;
    transform: translateX(-60%);
    min-width: 160px;
    max-width: 90vw;
  }
} */

.navbar-nav > li.dropdown.open > .dropdown-menu {
  display: block;
}

/* Touch/click support for dropdowns */
.navbar-nav > li.dropdown > a {
  cursor: pointer;
}

@media (max-width: 767px) {
  .navbar {
    flex-wrap: nowrap;
    padding: 0 5px;
  }
  .navbar-header {
    width: auto;
    flex: 0 0 auto;
    text-align: left;
    float: none;
  }
  .v3-logo-mobile {
    width: auto;
    margin: 0;
    text-align: left;
  }
  .v3-logo-mobile img {
    max-width: 100px;
    width: 22vw;
  }
  .navbar-menu-fullwidth {
    overflow-x: auto;
    flex-wrap: nowrap;
    padding: 10px 0;
    min-width: 0;
  }
  .navbar-menu-fullwidth ul.navbar-nav {
    gap: 6px;
  }
}
</style>
<script>
// Dropdown open/close on click for mobile
(function() {
  document.addEventListener('DOMContentLoaded', function() {
    var dropdowns = document.querySelectorAll('.navbar-nav > li.dropdown');
    dropdowns.forEach(function(drop) {
      drop.addEventListener('click', function(e) {
        // Only toggle if clicking the parent link
        if (e.target === drop.querySelector('a.dropdown-toggle')) {
          e.preventDefault();
          // Close others
          dropdowns.forEach(function(d) { if (d !== drop) d.classList.remove('open'); });
          drop.classList.toggle('open');
        }
      });
    });
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.navbar-nav')) {
        dropdowns.forEach(function(d) { d.classList.remove('open'); });
      }
    });
  });
})();
</script>