<?php $accordeonId = rand() ?>
<div id="accordion_<?php echo $accordeonId; ?>" class="accordion-item">
    <div id="accordionHeader_<?php echo $accordeonId; ?>" class="accordion-header">
      <h3>Section 1</h3>
      <span class="accordion-icon"></span>
    </div>
    <div id="accordionContent_<?php echo $accordeonId; ?>" class="accordion-content">
      <p>Content for section 1 goes here.</p>
    </div>
</div>
<script>
    var expanded = false;
    const accordionItem = document.getElementById("accordion_<?php echo $accordeonId; ?>");
    const accordionItemHeader = document.getElementById("accordionHeader_<?php echo $accordeonId; ?>");
    const accordionItemContent = document.getElementById("accordionContent_<?php echo $accordeonId; ?>" );
    accordionItemHeader.onclick = function(){
        expanded = !expanded;
        if(expanded && accordionItem){
            accordionItem.classList.add('active');
        }else if (accordionItem){
            accordionItem.classList.remove('active');
        }
        if(expanded && accordionItemContent){
           accordionItemContent.classList.add('active');
        }else if (accordionItemContent){
            accordionItemContent.classList.remove('active');
        }
    };
</script>