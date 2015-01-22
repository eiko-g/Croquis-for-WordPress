<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).on("click", ".add-smily",
function() {
        var myField;
        tag = ' ' + jQuery(this).data("smilies") + ' ';
        if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
            myField = document.getElementById('comment');
        } else {
            return false;
        }
        if (document.selection) {
            myField.focus();
            sel = document.selection.createRange();
            sel.text = tag;
            myField.focus();
        }
        else if (myField.selectionStart || myField.selectionStart == '0') {
            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            var cursorPos = endPos;
            myField.value = myField.value.substring(0, startPos)
                          + tag
                          + myField.value.substring(endPos, myField.value.length);
            cursorPos += tag.length;
            myField.focus();
            myField.selectionStart = cursorPos;
            myField.selectionEnd = cursorPos;
        }
        else {
            myField.value += tag;
            myField.focus();
        }
    return false;
});
/* ]]> */
</script>
<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_question.gif" alt="?" data-smilies=":?:" title="?" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_razz.gif" alt="razz" data-smilies=":razz:" title="razz" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_sad.gif" alt="sad" data-smilies=":sad:" title="sad" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_evil.gif" alt="evil" data-smilies=":evil:" title="evil" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_exclaim.gif" alt="!" data-smilies=":!:" title="!" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_smile.gif" alt="smile" data-smilies=":smile:" title="smile" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_redface.gif" alt="oops" data-smilies=":oops:" title="oops" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_biggrin.gif" alt="grin" data-smilies=":grin:" title="grin" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_surprised.gif" alt="eek" data-smilies=":eek:" title="eek" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_eek.gif" alt="shock" data-smilies=":shock:" title="shock" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_confused.gif" alt="???" data-smilies=":???:" title="???" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_cool.gif" alt="cool" data-smilies=":cool:" title="cool" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_lol.gif" alt="lol" data-smilies=":lol:" title="lol" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_mad.gif" alt="mad" data-smilies=":mad:" title="mad" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_twisted.gif" alt="twisted" data-smilies=":twisted:" title="twisted" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_rolleyes.gif" alt="roll" data-smilies=":roll:" title="roll" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_wink.gif" alt="wink" data-smilies=":wink:" title="wink" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_idea.gif" alt="idea" data-smilies=":idea:" title="idea" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_arrow.gif" alt="arrow" data-smilies=":arrow:" title="arrow" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_neutral.gif" alt="neutral" data-smilies=":neutral:" title="neutral" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_cry.gif" alt="cry" data-smilies=":cry:" title="cry" />

<img class="add-smily" src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_mrgreen.gif" alt="mrgreen" data-smilies=":mrgreen:" title="mrgreen" />