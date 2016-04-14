// Create the defaults once
var pluginName = 'mosaicUSP';
var defaults = {
};

// The actual plugin constructor
export default function MosaicUSP(element, options) {
	this.element = element;
	this.options = $.extend(true, {}, defaults, options);
	this._defaults = defaults;
	this._name = pluginName;
	this.init();
}

MosaicUSP.prototype = {
	init: function() {
		this.currentShown = null;
		$(this.element).on('click', '.item', e => {
			var item = e.currentTarget;
			if(item === this.currentShown) {
				this._getUSPBlockFromItem(item).slideUp().removeClass('shown');
				this.currentShown = null;
				$(item).removeClass('selected');
			} else {
				this.loadItem(item);
				$(this.element).find('.item.selected').removeClass('selected');
				$(item).addClass('selected');
			}
		});
	},

	loadItem: function(item) {
		// Try to find it from dom first
		var id = $(item).data('id'),
			data = $(this.element).find('.usp-to-show[data-id="'+id+'"]').html();
		if(data) {
			this._animateNewItem(item, data);
		} else {
			//TODO get from network
		}
	},

	_animateNewItem: function(item, data) {
		var uspblock = this._getUSPBlockFromItem(item),
			$element = $(this.element),
			hidePrevious = function(cb) {
				if(uspblock.hasClass('shown')) {
					uspblock.fadeOut({
						duration: 200,
						complete: cb
					});
				} else {
					$element.find('.usp-block.shown').slideUp().removeClass('shown');
					cb();
				}
			};

		this.currentShown = item;

		hidePrevious(function() {
			uspblock.html(data);

			if(uspblock.hasClass('shown')) {
				uspblock.fadeIn(200);
			} else {
				uspblock.slideDown().addClass('shown');
			}
		});

	},

	_getUSPBlockFromItem: function(item) {
		return $(item).nextAll('.usp-block').first();
	}
};
