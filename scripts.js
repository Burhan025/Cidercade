// Duplicate Text for forthworth
jQuery(document).ready(function() {
  var richText = jQuery(".scrolltext .fl-rich-text");
  var paragraphs = richText.find("p");

  for (var i = 0; i < 2; i++) {
    paragraphs.each(function() {
      richText.append(jQuery(this).clone());
    });
  }
});

// Duplicate Text for Drink Page
jQuery(document).ready(function() {
  var richText = jQuery("#drink-scroll.scrolltext .fl-rich-text");
  var paragraphs = richText.find("p");

  for (var i = 0; i < 6; i++) {
    paragraphs.each(function() {
      richText.append(jQuery(this).clone());
    });
  }
});


//Sticky Header
jQuery(document).ready(function() {
  var prevScrollpos = window.pageYOffset;
  window.onscroll = function() {
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos || window.pageYOffset < 50) {
      document.getElementById("main-header").style.top = "0";
    } else {
      if (window.innerWidth < 992) {
        document.getElementById("main-header").style.top = "-110px";
      } else {
        document.getElementById("main-header").style.top = "-70px";
      }
    }
    prevScrollpos = currentScrollPos;
  }
});


//Add class to Food Menu
jQuery(document).ready(function() {
  // Select the second .df-spl-row element inside .food-menu .price_wrapper .tab
  var secondRow = jQuery('.food-menu .price_wrapper .tab .df-spl-row:nth-child(2)');
  // Add the "food-items" class to the selected element
  secondRow.addClass('food-items');
});


//Break food menu into two column
jQuery(document).ready(function() {
  var elementsData = [
    { selector: ".tab-content1 #1_1684145836 .food-items .name-price-desc" },
    { selector: ".tab-content1 #2_1684145836 .food-items .name-price-desc" },
    { selector: ".tab-content1 #3_1684145836 .food-items .name-price-desc" },
    { selector: ".tab-content1 #4_1684145836 .food-items .name-price-desc" },
    { selector: ".tab-content1 #5_1684145836 .food-items .name-price-desc" },
    { selector: ".tab-content1 #all_8662939351 .name-price-desc" },
    { selector: ".tab-content1 #1_6404054898 .food-items .name-price-desc" },
    { selector: ".tab-content1 #2_6404054898 .food-items .name-price-desc" },
    { selector: ".tab-content1 #3_6404054898 .food-items .name-price-desc" },
    { selector: ".tab-content1 #4_6404054898 .food-items .name-price-desc" },
    { selector: ".tab-content1 #5_6404054898 .food-items .name-price-desc" },
    { selector: ".tab-content1 #6_6404054898 .food-items .name-price-desc" },
    { selector: ".tab-content1 #all_3171868341 .name-price-desc" }
];

  jQuery.each(elementsData, function(index, data) {
    var elements = jQuery(data.selector);
    var midpoint = Math.ceil(elements.length / 2);
    elements.slice(0, midpoint).wrapAll('<div class="column-one"></div>');
    elements.slice(midpoint).wrapAll('<div class="column-two"></div>');
  });
});