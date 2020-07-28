<div id="navBarContainer" xmlns="http://www.w3.org/1999/html">
    <nav class="navBar">
        <span role="link" tabindex="0" onclick="openPage('index.php')" class="logo">
            Entropy
        </span>

        <div class="group">
            <div class="navItem">
                <span role="link" tabindex="0" onclick='openPage("search.php")' class="navItemLink">
                    Search
                    <img src="assets/images/icons/search.png" class="icon" alt="Search" >
                </span>
            </div>
        </div>

        <div class="group">

            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">Browse</span>
            </div>

            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('music.php')" class="navItemLink">Your music</span>
            </div>

            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('settings.php')" class="navItemLink"><?php echo $userLoggedIn->getFirstAndLastName() ?></span>
            </div>
        </div>
    </nav>
</div>
