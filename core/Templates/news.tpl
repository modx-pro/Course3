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
	{else}
		<a href="/news/">&larr; Назад</a>
		{parent}
	{/if}
{/block}