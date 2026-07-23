@extends('layouts.master')
@section('content')

 
  
  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/page-title-bg.jpg);">
      <div class="container position-relative">
        <h1>Get a Quote</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Get A Quote</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Get A Quote Section -->
    <section id="get-a-quote" class="get-a-quote section">

      <div class="container">

        <div class="row g-0" data-aos="fade-up" data-aos-delay="100">

          <div class="col-lg-5 quote-bg" >
          <img src="/storage/{{$produit->produit->image}}" alt="" class="img-fluid services-img">
          <div class="services-list">
              <h3> <span style="color:black">{{$produit->produit->nom}}</span></h3>
              <h3> Type:  <span style="color:black"> {{$produit->produit->categorie->nom}}</span></h3>
              <h3>Poids: <span style="color:black">  {{$produit->produit->taille->taille}}</span></h3>
              <h3>Quantité Disponible:  <span style="color:black"> {{$produit->quantite}}</span></h3>
              <h3>Prix: <span style="color:black">  {{$produit->prix}}</span></h3>
            </div>
          </div>

          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
            <form action="forms/get-a-quote.php" method="post" class="php-email-form" id="quoteForm">
  <h3>Passer une commande</h3>

  <div class="row gy-4">

    <div class="col-md-6">
      <label>Quantité à Commander</label>
      <input type="number" 
             name="qte_commande" 
             id="qte_commande"
             min="5" 
             max="{{ $produit->quantite }}" 
             value="5"
             class="form-control" 
             required>
    </div>

    <div class="col-md-6">
      <label>Prix unitaire</label>
      <input type="text" class="form-control" value="{{ number_format($produit->prix, 0, ',', ' ') }} FCFA" disabled>
      <input type="hidden" name="idproduit" value="{{ $produit->idproduit }}">
    </div>

    <div class="col-12">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="souhaite_livraison" id="souhaite_livraison" value="1">
        <label class="form-check-label" for="souhaite_livraison">
          Je souhaite être livré
        </label>
      </div>
    </div>

    <!-- Bloc livraison : masqué par défaut, affiché seulement si la case est cochée -->
    <div id="bloc_livraison" class="row gy-4" style="display:none;">

      <div class="col-md-6">
        <label>Zone de Livraison</label>
       <select name="zone_livraison" id="zone_livraison" class="form-control">
          <option value="">Sélectionner une zone</option>
          @foreach($quartiers as $quartier)
              <option value="{{ $quartier->id }}" 
                      data-frais="{{ $quartier->service->prix ?? 0 }}">
                  {{ $quartier->nom_quartier }}
                  @if($quartier->service)
                      (+{{ number_format($quartier->service->prix, 0, ',', ' ') }} FCFA)
                  @endif
              </option>
          @endforeach
      </select>
      </div>

      <div class="col-md-6">
        <label>Adresse précise</label>
        <input type="text" name="adresse_livraison" id="adresse_livraison" class="form-control" placeholder="Ex: Rue 12, près de la pharmacie...">
      </div>

    </div>

    <div class="col-lg-12">
      <h4>Vos informations</h4>
    </div>

    <div class="col-12">
      <input type="text" name="name" class="form-control" placeholder="Nom" required>
    </div>

    <div class="col-12">
      <input type="email" class="form-control" name="email" placeholder="Email" required>
    </div>

    <div class="col-12">
      <input type="text" class="form-control" name="phone" placeholder="Téléphone" required>
    </div>

    <div class="col-12">
      <textarea class="form-control" name="message" rows="4" placeholder="Message (optionnel)"></textarea>
    </div>

    <!-- Récapitulatif du total (affichage uniquement, recalculé côté serveur pour la sécurité) -->
    <div class="col-12">
      <div class="border rounded p-3 bg-light">
        <div class="d-flex justify-content-between">
          <span>Sous-total (<span id="recap_qte">1</span> article(s))</span>
          <span id="recap_soustotal">0 FCFA</span>
        </div>
        <div class="d-flex justify-content-between" id="recap_frais_ligne" style="display:none;">
          <span >Frais de livraison</span>
          <span id="recap_frais">0 FCFA</span>
        </div>
        <hr class="my-2">
        <div class="d-flex justify-content-between fw-bold fs-5">
          <span>Total</span>
          <span id="recap_total">0 FCFA</span>
        </div>
      </div>
    </div>

    <div class="col-12 text-center">
      <div class="loading">Chargement</div>
      <div class="error-message"></div>
      <div class="sent-message">Votre commande a été envoyée avec succès. Merci !</div>
      <button type="submit">Confirmer la commande</button>
    </div>

  </div>
</form>
          </div><!-- End Quote Form -->

        </div>

      </div>

    </section><!-- /Get A Quote Section -->

  </main>



@endsection