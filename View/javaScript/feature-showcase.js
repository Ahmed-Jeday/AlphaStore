import gsap from 'https://esm.sh/gsap@3.13.0'
import Draggable from 'https://esm.sh/gsap@3.13.0/Draggable'
import { Pane } from 'https://esm.sh/tweakpane@4.0.4'
gsap.registerPlugin(Draggable)

const section = document.querySelector('section.feature-showcase')
const column = section.querySelector('.feature-showcase__column--left')
let detailsElements = section.querySelectorAll('.feature-showcase__detail')
const nextButton = section.querySelector('.feature-showcase__button[data-action="next"]')
const previousButton = section.querySelector('.feature-showcase__button[data-action="previous"]')
const exitButton = section.querySelector('.feature-showcase__button[data-action="exit"]')

const config = {
  theme: 'dark',
  debug: false,
}

const ctrl = new Pane({
  title: 'config',
  expanded: true,
})

const update = () => {
  document.documentElement.dataset.theme = config.theme
  document.documentElement.dataset.debug = config.debug
}

const sync = (event) => {
  if (
    !document.startViewTransition ||
    event.target.controller.view.labelElement.innerText !== 'theme'
  )
    return update()
  document.startViewTransition(() => update())
}


ctrl.addButton({ title: 'reset' }).on('click', () => {
  const currentIndex = getOpenDetails()

  if (currentIndex !== -1) {
    detailsElements[currentIndex].open = false
  }
  const details = column.innerHTML
  column.innerHTML = ''
  requestAnimationFrame(() => {
    column.innerHTML = details
    detailsElements = section.querySelectorAll('details[name="feature"]')
  }) 
})

ctrl.addBinding(config, 'debug')

ctrl.addBinding(config, 'theme', {
  label: 'theme',
  options: {
    system: 'system',
    light: 'light',
    dark: 'dark',
  },
})

ctrl.on('change', sync)
update()

// make tweakpane panel draggable
const tweakClass = 'div.tp-dfwv'
const d = Draggable.create(tweakClass, {
  type: 'x,y',
  allowEventDefault: true,
  trigger: tweakClass + ' button.tp-rotv_b',
})
document.querySelector(tweakClass).addEventListener('dblclick', () => {
  gsap.to(tweakClass, {
    x: `+=${d[0].x * -1}`,
    y: `+=${d[0].y * -1}`,
    onComplete: () => {
      gsap.set(tweakClass, { clearProps: 'all' })
    },
  })
})

// Navigation functionality for details elements
const getOpenDetails = () => {
  return Array.from(detailsElements).findIndex(details => details.open)
}

nextButton?.addEventListener('click', () => {
  const currentIndex = getOpenDetails()

  if (currentIndex !== -1) {
    detailsElements[currentIndex].open = false
    const nextIndex = (currentIndex + 1) % detailsElements.length
    detailsElements[nextIndex].open = true
  }
})

previousButton?.addEventListener('click', () => {
  const currentIndex = getOpenDetails()

  if (currentIndex !== -1) {
    detailsElements[currentIndex].open = false
    const previousIndex = (currentIndex - 1 + detailsElements.length) % detailsElements.length
    detailsElements[previousIndex].open = true
  }
})

exitButton?.addEventListener('click', () => {
  const currentIndex = getOpenDetails()

  if (currentIndex !== -1) {
    detailsElements[currentIndex].open = false
  }
})

const syncState = async () => {
  if (!section.matches(':has([open])')) {
    section.dataset.checkingDetails = false
  } else {
    await Promise.allSettled(section.getAnimations({ subtree: true }).map(a => a.finished))
    section.dataset.checkingDetails = true
  }
}

section.addEventListener('toggle', syncState, true)

// E-commerce interactions
const ctaButtons = section.querySelectorAll('.product-cta__button')
ctaButtons.forEach(btn => {
  btn.addEventListener('click', (e) => {
    const isPrimary = btn.classList.contains('product-cta__button--primary')
    if (isPrimary) {
      gsap.to(btn, {
        scale: 0.95,
        duration: 0.1,
        yoyo: true,
        repeat: 1,
        onComplete: () => {
          btn.textContent = 'Ajouté ✓'
          btn.style.background = '#2d8a4e'
          setTimeout(() => {
            btn.textContent = 'Ajouter au panier'
            btn.style.background = ''
          }, 2000)
        }
      })
    }
  })
})