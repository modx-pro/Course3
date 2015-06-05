<nav class="navbar navbar-default">
	<div class="navbar-header">
		<a class="navbar-brand" href="/">Course 3</a>
	</div>
	<ul class="nav navbar-nav">
		{set $pages = $_controller->getMenu()}
		{foreach $pages as $name => $page}
			{if $_controller->name == $name}
				<li class="active">
					<a href="#" style="cursor: default;" onclick="return false;">{$page.title}</a>
				</li>
			{else}
				<li><a href="{$page.link}">{$page.title}</a></li>
			{/if}
		{/foreach}
	</ul>
</nav>