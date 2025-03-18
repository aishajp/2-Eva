import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import Welcome from './Welcome.jsx'
import './scss/style.scss'
import * as bootstrap from 'bootstrap'
import $ from 'jquery'
window.$ = $;
window.jQuery = $;

const root = createRoot(document.getElementById('root'))
root.render(
  <StrictMode>
    <Welcome />
  </StrictMode>,
)
