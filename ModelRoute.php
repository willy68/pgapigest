<?php  
  return '   $route->get("/entreprise/{entreprise_id:\d+}/'.$model_name.'/{id:\d+}", '.$model_class.'Controller::class . "::get", "'.$model_name.'.get");
  $route->get("/entreprise/{entreprise_id:\d+}/'.$model_name.'s", '.$model_class.'Controller::class . "::list", "'.$model_name.'.all");
  $route->get("/entreprise/{entreprise_id:\d+}/'.$model_name.'/list", '.$model_class.'Controller::class . "::list", "'.$model_name.'.list");
  $route->post("/entreprise/{entreprise_id:\d+}/'.$model_name.'", '.$model_class.'Controller::class . "::create", "'.$model_name.'.create");
  $route->put("/entreprise/{entreprise_id:\d+}/'.$model_name.'/{id:\d+}", '.$model_class.'Controller::class . "::update", "'.$model_name.'.update");
  $route->delete("/entreprise/{entreprise_id:\d+}/'.$model_name.'/{id:\d+}", '.$model_class.'Controller::class . "::delete", "'.$model_name.'.delete");

';