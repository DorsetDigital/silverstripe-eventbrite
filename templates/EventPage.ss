<div class="container">
    <div class="row pt-4 page__introholder">
        <div class="col-8 col-md-10">
            <h1 class="page__title">$Title</h1>
            $Start.Nice
            <% if $Summary %>
                <p>$Summary.NoHTML</p>
            <% end_if %>
        </div>
        <div class="col-4 col-md-2" id="navbar__logoholder">
            <% if $SiteConfig.MainLogo %>
                <img src="$SiteConfig.MainLogo.URL" alt="$SiteConfig.SiteTitle"/>
            <% end_if %>
        </div>
    </div>
    <div class="row py-2">
        <div class="col breadcrumbs">
            <a href="/events">Events </a> &gt;
            $Title
        </div>
    </div>
    <div class="row my-4">
        <div class="col-12 col-sm-6 eventpage__descriptionholder">
            $Description

            <p class="my-4"><a class="btn btn-primary" href="$EBURL" target="_blank" rel="noopener">Click here for tickets</a></p>
        </div>
        <div class="col-12 col-sm-6">
            <% if $Image %>
                <img src="$Image" class="img-fluid" alt="$Title"/>
            <% end_if %>
        </div>
    </div>
</div>