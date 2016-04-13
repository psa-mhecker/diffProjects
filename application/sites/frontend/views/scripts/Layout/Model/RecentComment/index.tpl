{$data.ZONE_TITLE}
               <div class="item-list">
				{section name=index loop=$data.COMMENTS}
				<ul><li><a href="#{$data.COMMENTS[index].OBJECT_ID}">[{$data.COMMENTS[index].COMMENT_PSEUDO}] {$data.COMMENTS[index].COMMENT_TITLE}</a></li></ul>&nbsp;&nbsp;il y a 1 week 1 hour ago
				{/section}
               </div>