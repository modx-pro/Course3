{extends '_base.tpl'}

{block 'title'}
	{$title} / {parent}
{/block}

{block 'content'}
	{if $items}
		{foreach $items as $item}
			<div class="news">
				<h3><a href="/news/{$item.alias}">{$item.pagetitle}</a></h3>
				<p>{$item.text}</p>
				{if $item.cut}
					<a href="/news/{$item.alias}" class="btn btn-default">Читать далее &rarr;</a>
				{/if}
			</div>
		{/foreach}
		{if $pagination}
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
		{/if}
	{else}
		<a href="/news/">&larr; Назад</a>
		{parent}
	{/if}
{/block}