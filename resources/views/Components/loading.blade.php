<div id="loader" style="display: none" class="loader">
    <div class="containerLoader">
        <div class="dot"></div>
      </div>
</div>

<style>
    .loader{
        width: 100vw;
        height: 100vh;
        z-index: 99999;
        place-items: center;
        background-color: rgba(1,1,1,0.4);
        backdrop-filter: blur(2px); 
    }
  .containerLoader {
    --uib-size: 45px;
    --uib-color: black;
    --uib-speed: 2s;
    position: relative;
    height: var(--uib-size);
    width: var(--uib-size);
  }

  .containerLoader::before,
  .containerLoader::after,
  .dot::before,
  .dot::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    border-radius: 50%;
    background-color: var(--uib-color);
    animation: pulse var(--uib-speed) linear infinite;
    transform: scale(0);
    opacity: 0;
    transition: background-color 0.3s ease;
  }

  .containerLoader::after {
    animation-delay: calc(var(--uib-speed) / -4);
  }

  .dot::before {
    animation-delay: calc(var(--uib-speed) * -0.5);
  }

  .dot::after {
    animation-delay: calc(var(--uib-speed) * -0.75);
  }

  @keyframes pulse {
    0% {
      transform: scale(0);
      opacity: 1;
    }
    100% {
      transform: scale(1);
      opacity: 0;
    }
  }
</style>

