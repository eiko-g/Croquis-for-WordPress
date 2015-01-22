/* Ajax 评论翻页 */
jQuery(document).on("click", "#commentnav a",
    function() {
        var baseUrl = jQuery(this).attr("href"),
        commentsHolder = jQuery("#comments-body"),
        id = jQuery(this).parent().data("post-id"),
        page = 1,
        concelLink = jQuery("#cancel-comment-reply-link");
        /comment-page-/i.test(baseUrl) ? page = baseUrl.split(/comment-page-/i)[1].split(/(\/|#|&).*jQuery/)[0] : /cpage=/i.test(baseUrl) && (page = baseUrl.split(/cpage=/)[1].split(/(\/|#|&).*jQuery/)[0]);
        concelLink.click();
        var ajax_data = {
            action: "ajax_comment_page_nav",
            um_post: id,
            um_page: page
        };
        //add loading
        commentsHolder.html('<div id="loading-comments">Loading...</div>');
        jQuery.post(ajaxcomment.ajax_url, ajax_data,
        function(data) {
            commentsHolder.html(data);
            //remove loading
            jQuery("body, html").animate({
                scrollTop: commentsHolder.offset().top - 50
            },
            1e3)
        });
        return false;
    })


/* 点赞 */
jQuery.fn.postLike = function() {
    var thislike = jQuery(this);
    if (thislike.hasClass('done')) {
        return false;
    } else {
        thislike.addClass('done');
        var id = thislike.data("id"),
        action = thislike.data('action'),
        rateHolder = thislike.children('.count');
        var ajax_data = {
            action: "bigfa_like",
            um_id: id,
            um_action: action
        };
        jQuery.post(ajaxurl.ajax_url, ajax_data,
        function(data) {
            jQuery(rateHolder).html(data);
            thislike.removeClass('icon-favorite-outline').addClass('icon-favorite animation');
        });
        return false;
    }
};
jQuery(document).on("click", ".favorite",
function() {
    jQuery(this).postLike();
});

jQuery(document).click(
      function(){
        jQuery('.share-element').removeClass('open').hide();
    }
    );

//文章分享
jQuery(document).on("click", ".share",
    function() {
        var this_menu = jQuery(this).find('.share-element');
        if (!this_menu.hasClass('open')) {
            jQuery('.share-element').removeClass('open').hide();
            this_menu.addClass('open').show();
            return false;
        } else{
            this_menu.removeClass('open').hide();
            return false;
        };
});
jQuery(document).on("click", ".share-twitter",
    function() {
    var this_link = jQuery(this).parent().parent().data('link');
    console.log(this_link);
    var this_title = jQuery(this).parent().parent().data('title');
    console.log(this_title);
    window.open(
        'https://twitter.com/intent/tweet?text='+this_title+'&url='+this_link,
        'Share to Twitter',
        'width=500,height=375');
    return false;
});
jQuery(document).on("click", ".share-weibo",
    function() {
    var this_link = jQuery(this).parent().parent().data('link');
    console.log(this_link);
    var this_title = jQuery(this).parent().parent().data('title');
    console.log(this_title);
    window.open(
        'http://service.weibo.com/share/share.php?url='+this_link+'&title='+this_title,
        'Share to weibo',
        'width=500,height=375');
    return false;
});

//Ajax 文章加载
jQuery(document).on("click", "#show-more",
function() {
    if (jQuery(this).hasClass('is-loading')) {
        return false;
    }
     else {
        var paged = jQuery(this).data("paged"),
        total = jQuery(this).data("total"),
        category = jQuery(this).data("cate"),
        tag = jQuery(this).data("tag"),
        search = jQuery(this).data("search"),
        author = jQuery(this).data("author");
        var ajax_data = {
            action: "ajax_index_post",
            paged: paged,
            total: total,
            category:category,
            author:author,
            tag:tag,
            search:search
        };
        jQuery(this).html('Loading...').addClass('is-loading')
         jQuery.post(ajaxurl.ajax_url, ajax_data,
        function(data) {
            jQuery('#page-navi-index').remove();
            jQuery("#content").append(data);//这里是包裹文章的容器名
        });
        return false;
    }
});

//回到顶部
var bigfa_scroll = {
    drawCircle: function(id, percentage, color) {
        var width = jQuery(id).width();
        var height = jQuery(id).height();
        var radius = parseInt(width / 2.20);
        var position = width;
        var positionBy2 = position / 2;
        var bg = jQuery(id)[0];
        id = id.split("#");
        var ctx = bg.getContext("2d");
        var imd = null;
        var circ = Math.PI * 2;
        var quart = Math.PI / 2;
        ctx.clearRect(0, 0, width, height);
        ctx.beginPath();
        ctx.strokeStyle = color;
        ctx.lineCap = "square";
        ctx.closePath();
        ctx.fill();
        ctx.lineWidth = 3;
        imd = ctx.getImageData(0, 0, position, position);
        var draw = function(current, ctxPass) {
            ctxPass.putImageData(imd, 0, 0);
            ctxPass.beginPath();
            ctxPass.arc(positionBy2, positionBy2, radius, -(quart), ((circ) * current) - quart, false);
            ctxPass.stroke();
        }
        draw(percentage / 100, ctx);
    },
    backToTop: function($this) {
        $this.click(function() {
            jQuery("body,html").animate({
                scrollTop: 0
            },
            800);
            return false;
        });
    },
    scrollHook: function($this, color) {
        color = color ? color: "#6aaaaa";
        $this.scroll(function() {
            var docHeight = (jQuery(document).height() - jQuery(window).height()),
            $windowObj = $this,
            $per = jQuery(".per"),
            percentage = 0;
            defaultScroll = $windowObj.scrollTop();
            percentage = parseInt((defaultScroll / docHeight) * 100);
            var backToTop = jQuery("#backtoTop");
            if (backToTop.length > 0) {
                if ($windowObj.scrollTop() > 200) {
                    backToTop.addClass("button--show");
                } else {
                    backToTop.removeClass("button--show");
                }
                $per.attr("data-percent", percentage);
                bigfa_scroll.drawCircle("#backtoTopCanvas", percentage, color);
            }

        });
    }
}

jQuery(document).ready(function() {
    jQuery("body").append('<div id="backtoTop" data-action="gototop"><canvas id="backtoTopCanvas" width="48" height="48"></canvas><div class="per"></div></div>');
    var T = bigfa_scroll;
    T.backToTop(jQuery("#backtoTop"));
    T.scrollHook(jQuery(window), "#6aaaaa");
});