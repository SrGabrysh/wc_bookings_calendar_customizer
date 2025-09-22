/**
 * JavaScript moderne pour WooCommerce Bookings Customizer
 * Améliore l'expérience utilisateur du calendrier
 */

(function ($) {
  "use strict";

  /**
   * Classe principale pour gérer le calendrier moderne
   */
  class ModernBookingCalendar {
    constructor() {
      this.init();
    }

    /**
     * Initialisation
     */
    init() {
      this.bindEvents();
      this.enhanceCalendar();
      this.addAnimations();
      this.improveAccessibility();
      this.addTouchSupport();
    }

    /**
     * Liaison des événements
     */
    bindEvents() {
      $(document).ready(() => {
        this.onDocumentReady();
      });

      // Événements de changement de date
      $(document).on(
        "change",
        ".booking_date_month, .booking_date_day, .booking_date_year",
        () => {
          this.onDateChange();
        }
      );

      // Événements de sélection de créneaux
      $(document).on("click", ".block-picker a", (e) => {
        this.onTimeSlotClick(e);
      });

      // Événements de hover sur les créneaux
      $(document).on("mouseenter", ".block-picker a", (e) => {
        this.onTimeSlotHover(e, true);
      });

      $(document).on("mouseleave", ".block-picker a", (e) => {
        this.onTimeSlotHover(e, false);
      });

      // Amélioration des sélecteurs
      $(document).on(
        "change",
        "#wc-bookings-form-start-time, #wc-bookings-form-end-time",
        () => {
          this.onTimeSelectChange();
        }
      );
    }

    /**
     * Actions à effectuer quand le document est prêt
     */
    onDocumentReady() {
      this.addLoadingStates();
      this.customizeCalendarHeader();
      this.addCalendarIcons();
      this.addPriceAnimations();
      this.fixContainerSizing();
    }

    /**
     * Amélioration du calendrier jQuery UI
     */
    enhanceCalendar() {
      // Attendre que le datepicker soit initialisé
      setTimeout(() => {
        this.customizeDatepicker();
      }, 500);
    }

    /**
     * Personnalisation du datepicker jQuery UI
     */
    customizeDatepicker() {
      // Ajouter des classes personnalisées
      $(".ui-datepicker").addClass("modern-calendar");

      // Améliorer la navigation
      $(".ui-datepicker-prev, .ui-datepicker-next").each(function () {
        const $this = $(this);
        const isNext = $this.hasClass("ui-datepicker-next");
        const icon = isNext ? "→" : "←";
        $this.html(`<span class="calendar-nav-icon">${icon}</span>`);
      });

      // Ajouter des animations aux jours
      $(".ui-datepicker td a").addClass("calendar-day");
    }

    /**
     * Ajout d'animations
     */
    addAnimations() {
      // Animation d'apparition du formulaire
      $("#wc-bookings-booking-form").addClass("animate-in");

      // Animation des créneaux horaires
      this.animateTimeSlots();
    }

    /**
     * Animation des créneaux horaires
     */
    animateTimeSlots() {
      $(".block-picker li").each(function (index) {
        $(this).css("animation-delay", `${index * 0.1}s`);
        $(this).addClass("time-slot-animate");
      });
    }

    /**
     * Gestion du changement de date
     */
    onDateChange() {
      // Ajouter un effet de loading
      this.showLoadingState(".wc-bookings-time-block-picker");

      // Animation de transition
      $(".block-picker").fadeOut(200, () => {
        setTimeout(() => {
          $(".block-picker").fadeIn(300);
          this.hideLoadingState(".wc-bookings-time-block-picker");
          this.animateTimeSlots();
        }, 500);
      });
    }

    /**
     * Gestion du clic sur un créneau
     */
    onTimeSlotClick(e) {
      e.preventDefault();

      const $slot = $(e.currentTarget);
      const $li = $slot.closest("li");

      // Retirer la sélection des autres créneaux
      $(".block-picker li").removeClass("selected");

      // Ajouter la sélection au créneau cliqué
      $li.addClass("selected");

      // Animation de sélection
      $slot.addClass("slot-selected");

      // Effet de ripple
      this.createRippleEffect($slot, e);

      // Mettre à jour le prix avec animation
      this.updatePriceWithAnimation();
    }

    /**
     * Gestion du hover sur les créneaux
     */
    onTimeSlotHover(e, isEntering) {
      const $slot = $(e.currentTarget);

      if (isEntering) {
        $slot.addClass("slot-hover");
        this.showSlotPreview($slot);
      } else {
        $slot.removeClass("slot-hover");
        this.hideSlotPreview();
      }
    }

    /**
     * Gestion du changement de sélecteur de temps
     */
    onTimeSelectChange() {
      this.updatePriceWithAnimation();
      this.addSelectAnimation();
    }

    /**
     * Affichage d'un aperçu du créneau
     */
    showSlotPreview($slot) {
      const slotText = $slot.text().trim();
      const $preview = $('<div class="slot-preview"></div>').text(slotText);

      $("body").append($preview);

      // Positionner l'aperçu
      const offset = $slot.offset();
      $preview.css({
        top: offset.top - 40,
        left: offset.left + $slot.outerWidth() / 2 - $preview.outerWidth() / 2,
      });

      $preview.fadeIn(200);
    }

    /**
     * Masquer l'aperçu du créneau
     */
    hideSlotPreview() {
      $(".slot-preview").fadeOut(200, function () {
        $(this).remove();
      });
    }

    /**
     * Créer un effet de ripple
     */
    createRippleEffect($element, event) {
      const $ripple = $('<span class="ripple"></span>');
      const rect = $element[0].getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const x = event.clientX - rect.left - size / 2;
      const y = event.clientY - rect.top - size / 2;

      $ripple.css({
        width: size,
        height: size,
        left: x,
        top: y,
      });

      $element.append($ripple);

      setTimeout(() => {
        $ripple.remove();
      }, 600);
    }

    /**
     * Mise à jour du prix avec animation
     */
    updatePriceWithAnimation() {
      const $price = $(".wc-bookings-booking-cost");

      if ($price.length) {
        $price.addClass("price-updating");

        setTimeout(() => {
          $price.removeClass("price-updating").addClass("price-updated");

          setTimeout(() => {
            $price.removeClass("price-updated");
          }, 1000);
        }, 300);
      }
    }

    /**
     * Animation des sélecteurs
     */
    addSelectAnimation() {
      $(
        ".wc-bookings-start-time-container select, .wc-bookings-end-time-container select"
      ).addClass("select-changed");

      setTimeout(() => {
        $(".select-changed").removeClass("select-changed");
      }, 300);
    }

    /**
     * États de chargement
     */
    showLoadingState(selector) {
      const $element = $(selector);
      if ($element.length) {
        $element.addClass("loading-state");

        if (!$element.find(".loading-spinner").length) {
          $element.append('<div class="loading-spinner"></div>');
        }
      }
    }

    /**
     * Masquer les états de chargement
     */
    hideLoadingState(selector) {
      const $element = $(selector);
      $element.removeClass("loading-state");
      $element.find(".loading-spinner").remove();
    }

    /**
     * Ajouter les états de chargement
     */
    addLoadingStates() {
      // Style pour les états de chargement
      const loadingCSS = `
                <style>
                .loading-state {
                    position: relative;
                    opacity: 0.7;
                    pointer-events: none;
                }
                
                .loading-spinner {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 24px;
                    height: 24px;
                    border: 2px solid #f3f3f3;
                    border-top: 2px solid #3498db;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    z-index: 10;
                }
                
                @keyframes spin {
                    0% { transform: translate(-50%, -50%) rotate(0deg); }
                    100% { transform: translate(-50%, -50%) rotate(360deg); }
                }
                
                .price-updating {
                    transform: scale(0.95);
                    opacity: 0.7;
                    transition: all 0.3s ease;
                }
                
                .price-updated {
                    transform: scale(1.05);
                    transition: all 0.3s ease;
                }
                
                .slot-selected {
                    animation: selectedPulse 0.6s ease;
                }
                
                @keyframes selectedPulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                    100% { transform: scale(1); }
                }
                
                .ripple {
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.6);
                    animation: rippleEffect 0.6s linear;
                    pointer-events: none;
                }
                
                @keyframes rippleEffect {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
                
                .slot-preview {
                    position: absolute;
                    background: rgba(0, 0, 0, 0.8);
                    color: white;
                    padding: 8px 12px;
                    border-radius: 6px;
                    font-size: 12px;
                    z-index: 1000;
                    pointer-events: none;
                    display: none;
                }
                
                .time-slot-animate {
                    animation: slideInUp 0.5s ease forwards;
                    opacity: 0;
                }
                
                @keyframes slideInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .animate-in {
                    animation: fadeInUp 0.6s ease;
                }
                
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .select-changed {
                    animation: selectPulse 0.3s ease;
                }
                
                @keyframes selectPulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.02); }
                    100% { transform: scale(1); }
                }
                </style>
            `;

      $("head").append(loadingCSS);
    }

    /**
     * Personnaliser l'en-tête du calendrier
     */
    customizeCalendarHeader() {
      // Ajouter des icônes modernes aux boutons de navigation
      setTimeout(() => {
        $(".ui-datepicker-prev").attr("title", "Mois précédent");
        $(".ui-datepicker-next").attr("title", "Mois suivant");
      }, 500);
    }

    /**
     * Ajouter des icônes au calendrier
     */
    addCalendarIcons() {
      // Ajouter une icône de calendrier au label
      $(".wc-bookings-date-picker .label").prepend(
        '<span class="calendar-icon">📅 </span>'
      );
    }

    /**
     * Animations du prix
     */
    addPriceAnimations() {
      // Observer les changements de prix
      const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
          if (
            mutation.type === "childList" ||
            mutation.type === "characterData"
          ) {
            this.updatePriceWithAnimation();
          }
        });
      });

      const priceElement = document.querySelector(".wc-bookings-booking-cost");
      if (priceElement) {
        observer.observe(priceElement, {
          childList: true,
          subtree: true,
          characterData: true,
        });
      }
    }

    /**
     * Amélioration de l'accessibilité
     */
    improveAccessibility() {
      // Ajouter des labels ARIA
      $(".block-picker a").each(function () {
        const $this = $(this);
        const timeText = $this.text().trim();
        $this.attr("aria-label", `Sélectionner le créneau ${timeText}`);
      });

      // Ajouter la navigation au clavier
      $(document).on("keydown", ".block-picker a", (e) => {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          $(e.target).click();
        }
      });

      // Améliorer les sélecteurs
      $("select").each(function () {
        const $this = $(this);
        if (!$this.attr("aria-label")) {
          const label =
            $this.prev("label").text() ||
            $this.closest(".form-field").find("label").text();
          if (label) {
            $this.attr("aria-label", label);
          }
        }
      });
    }

    /**
     * Support tactile pour mobile
     */
    addTouchSupport() {
      // Améliorer les interactions tactiles sur les créneaux
      $(".block-picker a").on("touchstart", function () {
        $(this).addClass("touch-active");
      });

      $(".block-picker a").on("touchend", function () {
        $(this).removeClass("touch-active");
      });

      // CSS pour le support tactile
      const touchCSS = `
                <style>
                @media (hover: none) and (pointer: coarse) {
                    .block-picker a {
                        padding: 20px 12px !important;
                        min-height: 48px !important;
                    }
                    
                    .touch-active {
                        background: var(--primary-color, #3498db) !important;
                        color: white !important;
                        transform: scale(0.95) !important;
                    }
                    
                    .ui-datepicker td a {
                        min-width: 44px !important;
                        min-height: 44px !important;
                        padding: 14px !important;
                    }
                }
                </style>
            `;

      $("head").append(touchCSS);
    }

    /**
     * Forcer les dimensions du conteneur (approche directe)
     */
    fixContainerSizing() {
      console.log(
        "🔧 Google Calendar: Initialisation du conteneur avec dimensions fixes"
      );

      // Fonction pour forcer les dimensions
      const forceContainerDimensions = () => {
        const $bookingForm = $(
          "#wc-bookings-booking-form, .wc-bookings-booking-form"
        );
        const $summary = $(".single-product .product .summary");

        if ($bookingForm.length) {
          // Forcer les dimensions du formulaire
          $bookingForm.css({
            width: "450px",
            "min-width": "450px",
            "max-width": "none",
            overflow: "visible",
            position: "relative",
            "z-index": "1000",
            "margin-right": "20px",
          });

          // Forcer les dimensions du conteneur parent
          if ($summary.length) {
            $summary.css({
              "min-width": "500px",
              overflow: "visible",
            });
          }

          console.log("✅ Conteneur forcé à 450px de largeur");

          // Afficher les informations de debug
          this.logContainerInfo();
        }
      };

      // Appliquer immédiatement
      forceContainerDimensions();

      // Réappliquer après chargement complet
      setTimeout(forceContainerDimensions, 1000);
    }

    /**
     * Outils de debug pour la console
     */
    logContainerInfo() {
      const $bookingForm = $(
        "#wc-bookings-booking-form, .wc-bookings-booking-form"
      );
      const $calendar = $(".ui-datepicker");
      const $summary = $(".single-product .product .summary");

      console.group("📊 Debug Conteneur Google Calendar");

      if ($bookingForm.length) {
        console.log("📦 Formulaire booking:", {
          width: $bookingForm.width() + "px",
          height: $bookingForm.height() + "px",
          visible: $bookingForm.is(":visible"),
          overflow: $bookingForm.css("overflow"),
        });
      }

      if ($calendar.length) {
        console.log("📅 Calendrier UI:", {
          width: $calendar.width() + "px",
          height: $calendar.height() + "px",
          visible: $calendar.is(":visible"),
        });
      }

      if ($summary.length) {
        console.log("📄 Conteneur parent (.summary):", {
          width: $summary.width() + "px",
          overflow: $summary.css("overflow"),
        });
      }

      console.groupEnd();
    }

    /**
     * Afficher une notification de redimensionnement
     */
    showResizeNotification() {
      const $notification = $(`
        <div class="gcal-resize-notification" style="
          position: fixed;
          top: 20px;
          right: 20px;
          background: #4CAF50;
          color: white;
          padding: 12px 20px;
          border-radius: 8px;
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
          z-index: 9999;
          font-size: 14px;
          font-weight: 500;
          opacity: 0;
          transform: translateX(100%);
          transition: all 0.3s ease;
        ">
          📅 Calendrier ajusté automatiquement
        </div>
      `);

      $("body").append($notification);

      // Animation d'entrée
      setTimeout(() => {
        $notification.css({
          opacity: "1",
          transform: "translateX(0)",
        });
      }, 100);

      // Suppression automatique après 3 secondes
      setTimeout(() => {
        $notification.css({
          opacity: "0",
          transform: "translateX(100%)",
        });

        setTimeout(() => {
          $notification.remove();
        }, 300);
      }, 3000);
    }
  }

  /**
   * Utilitaires
   */
  const Utils = {
    /**
     * Debounce function
     */
    debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    },

    /**
     * Vérifier si on est sur mobile
     */
    isMobile() {
      return window.innerWidth <= 768;
    },

    /**
     * Vérifier le support des animations
     */
    supportsAnimations() {
      const el = document.createElement("div");
      return typeof el.style.animationName !== "undefined";
    },
  };

  /**
   * Initialisation quand jQuery est prêt
   */
  $(document).ready(function () {
    // Vérifier si WooCommerce Bookings est présent
    if ($("#wc-bookings-booking-form").length) {
      window.calendarInstance = new ModernBookingCalendar();
    }
  });

  /**
   * Outils de debug pour la console
   */
  window.GCalDebug = {
    /**
     * Tester les dimensions du conteneur
     */
    testContainerSize: function () {
      const $form = $("#wc-bookings-booking-form, .wc-bookings-booking-form");
      const $calendar = $(".ui-datepicker");

      console.group("🧪 Test Dimensions Conteneur");
      console.log(
        "Formulaire booking:",
        $form.length ? `${$form.width()}px × ${$form.height()}px` : "Non trouvé"
      );
      console.log(
        "Calendrier jQuery UI:",
        $calendar.length
          ? `${$calendar.width()}px × ${$calendar.height()}px`
          : "Non trouvé"
      );
      console.log("Calendrier visible:", $calendar.is(":visible"));
      console.groupEnd();

      return {
        formWidth: $form.width(),
        formHeight: $form.height(),
        calendarWidth: $calendar.width(),
        calendarHeight: $calendar.height(),
      };
    },

    /**
     * Forcer le redimensionnement manuel
     */
    forceResize: function (width = 450) {
      const $form = $("#wc-bookings-booking-form, .wc-bookings-booking-form");
      const $summary = $(".single-product .product .summary");

      $form.css({
        width: width + "px",
        "min-width": width + "px",
        overflow: "visible",
      });

      $summary.css({
        "min-width": width + 50 + "px",
        overflow: "visible",
      });

      console.log(`✅ Conteneur forcé à ${width}px`);
      return this.testContainerSize();
    },

    /**
     * Réinitialiser les styles
     */
    resetStyles: function () {
      const $form = $("#wc-bookings-booking-form, .wc-bookings-booking-form");
      $form.removeAttr("style");
      console.log("🔄 Styles réinitialisés");
    },

    /**
     * Afficher les informations complètes
     */
    info: function () {
      if (window.calendarInstance) {
        window.calendarInstance.logContainerInfo();
      } else {
        console.log("❌ Instance de calendrier non trouvée");
      }
    },
  };

  /**
   * Compatibilité avec les anciennes versions
   */
  window.ModernBookingCalendar = ModernBookingCalendar;
  window.BookingUtils = Utils;
})(jQuery);
