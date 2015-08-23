{extends '_base.tpl'}

{block 'title'}
	{$title} / {parent}
{/block}

{block 'content'}
	{if $items}
		<div id="news-wrapper">
			<div id="news-items">
				{insert '_news.tpl'}
			</div>
			<div id="news-pagination">
				{if $pagination}
					{insert '_pagination.tpl'}
				{/if}
			</div>
		</div>
	{else}
		<a href="/news/">&larr; Назад</a>
		{parent}
	{/if}
{/block}