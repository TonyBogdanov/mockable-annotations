![build](https://github.com/TonyBogdanov/mockable-annotations/workflows/build/badge.svg)
[![coverage](http://TonyBogdanov.github.io/mockable-annotations/coverage.svg)](http://TonyBogdanov.github.io/mockable-annotations/index.html)

## Introduction

[Doctrine Annotations](https://www.doctrine-project.org/projects/annotations.html) are a powerful tool that allows
specifying all sorts of meta information for classes, methods and properties, in a clean, semantic & predictable way.

One feature lacking in the default annotation readers is mock-ability, or the ability to inject annotations at runtime.\
This can be particularly useful when the annotated classes are part of a third party library that you have no control
over. The default strategy in this case would be to extend or override the entire class, but that is not always
possible and is definitely not maintainable.

This package introduces a mock-able annotation reader, with support for various ways of "instructing" it how to read
annotations. It supports overriding and merging class, method and property level annotations, and also exposes a lot
of interfaces for you to introduce your own functionality.

It works as a wrapper to an annotation reader instance of your choice, so you can still benefit from its features
(such as caching for example) with minimal effort.

## Installation

```bash
composer require tonybogdanov/mockable-annotations
```

\* *By default the reader has dependency only on the `doctrine/annotations` package, so if you intend to use
Doctrine's cached reader, make sure to also add `doctrine/cache` to your dependencies.*

## Providers

The included `MockableAnnotationReader` mocks annotations using annotation providers for class, method and property
level annotations respectively. Use any of the `[clear|get|set|add][Class|Method|Property]MockProvider(s)` methods
to register your providers. The methods expect instances of the respective interfaces, so you can even build your
own provider if required.

The package comes with 3 default providers: `ClassMockProvider`, `MethodMockProvider` and `PropertyMockProvider` with
support for override and merge strategies, optional filtering and priority (higher priority values are executed later).

## Examples

The following class definition:

```php
/**
 * @TestAnnotation("hello") 
 */
class TestClass {}
```

Can have its class annotations mocked, so that the reader actually sees this:

```php
/**
 * @AnotherTestAnnotation("world") 
 */
class TestClass {}
```

With the following example code:

```php
$newAnnotation = new AnotherTestAnnotation();
$newAnnotation->value = "world";

$reader = new MockableAnnotationReader( new AnnotationReader() );
$reader->addClassMockProvider( new ClassMockProvider(
                               
   [ $newAnnotation ],
   new OverrideStrategy(),
   new ClassNameFilter( TestClass::class )

) );

$reader->getClassAnnotations( TestClass::class );
// ...
```

Here we are using the `OverrideStrategy`, which ignores the original annotations and replaces them with the custom ones
passed to the `ClassMockProvider`. You can also use a `MergeStrategy`, which will merge the original annotations with
 the new ones instead. You can of course implement and pass your own strategy.
 
Additionally we are using a `ClassNameFilter`, so that the provider will only be applied if the class being read is
an instance of `TestClass`.

\* *If you need the filter to check if the class being read is __exactly__ an instance of `TestClass` and not an
instance of a class extending `TestClass`, you can pass `true` as a second argument to the `ClassNameFilter` filter.* 

Mocking methods and properties follows the same principles with a very similar API.\
Take a look at the source for reference.

## Convenience Methods

Registering providers can be quite a verbose task, so for most common scenarios the reader exposes a couple of
convenience methods instead.

- `[override|merge][Class|Method|Property]Annotations` - accepts a class name, (a method or property name), an array
of annotations and an optional priority, registering a simple class / method / property annotations provider, only
applicable in case the class / method / property being read matches the specified one.

- `[override|merge]Alias[Class|Method|Property]Annotations` - accepts two pairs of class names, (and method or
property names), where the first pair targets the class / method / property being read, and the second one targets a
corresponding class / method / property who's annotations will override / be merged with the annotations of the source.\
\
This can be useful when you don't want to specify annotations programmatically and want to get them from an "Alias" or
"Dummy" class / method / property.

- `[override|merge]AliasAnnotations` - probably the most *convenient* of all, this method accepts a source class name
and a target class name, then overrides / merges all class / method & property annotations from the target class with
the source class.\
\
Keep in mind that only methods & properties in the source class will be inspected and matched against the target class.
Annotations on methods and properties from the target (alias) class, which do not exist in the source one, will be
ignored.
