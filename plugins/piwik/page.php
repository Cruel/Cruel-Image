<?php

$template->set('title', 'Stats - '.CI_TITLE);
$template->place('header');
$template->inject('home/header.php');

?>


<iframe width="49%" height="250" src="/piwik/index.php?module=Widgetize&action=iframe&columns[]=nb_visits&widget=1&moduleToWidgetize=VisitsSummary&actionToWidgetize=getEvolutionGraph&idSite=1&period=day&date=today&disableLink=1&widget=1" scrolling="auto" frameborder="0" marginheight="0" marginwidth="0"></iframe>
<iframe width="49%" height="250" src="/piwik/index.php?module=Widgetize&action=iframe&widget=1&moduleToWidgetize=UserCountryMap&actionToWidgetize=visitorMap&idSite=1&period=day&date=today&disableLink=1&widget=1" scrolling="auto" frameborder="0" marginheight="0" marginwidth="0"></iframe>
<iframe width="49%" height="250" src="/piwik/index.php?module=Widgetize&action=iframe&widget=1&moduleToWidgetize=Live&actionToWidgetize=widget&idSite=1&period=day&date=today&disableLink=1&widget=1" scrolling="auto" frameborder="0" marginheight="0" marginwidth="0"></iframe>
<iframe width="49%" height="250" src="/piwik/index.php?module=Widgetize&action=iframe&widget=1&moduleToWidgetize=Actions&actionToWidgetize=getPageUrls&idSite=1&period=day&date=today&disableLink=1&widget=1" scrolling="auto" frameborder="0" marginheight="0" marginwidth="0"></iframe>


<?php

$template->add('body_extra', dirname(__FILE__).'/script.php');
$template->inject('home/footer.php');
$template->place('footer');