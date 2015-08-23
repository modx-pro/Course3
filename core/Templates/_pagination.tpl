<nav>
	<ul class="pagination">
		{foreach $pagination as $page => $type}
			{switch $type}
			{case 'first'}
				<li><a href="/news/">&laquo;</a></li>
			{case 'last'}
				<li><a href="/news/{$page}/">&raquo;</a></li>
			{case 'less', 'more'}
			{case 'current'}
				<li class="active"><a href="/news/{$page}/">{$page}</a></li>
			{case default}
				<li><a href="/news/{$page}/">{$page}</a></li>
			{/switch}
		{/foreach}
	</ul>
</nav>